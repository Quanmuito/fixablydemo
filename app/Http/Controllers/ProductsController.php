<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class ProductsController extends Controller
{
    protected $TOKEN;
    protected $baseUrl = 'https://careers-api.fixably.com';
    protected $STATUS;
    protected $FIELDS = ['device_type', 'manufacturer', 'statuses', 'technicians', 'notes'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $res = Curl::to($this->baseUrl . '/token')
        ->withData( array(
            'Name' => 'Quan Tran',
            'Email' => 'tranleminhquan1102@gmail.com'
        ))
        ->post();

        $this->TOKEN = json_decode($res)->token;

        $res = Curl::to($this->baseUrl . '/statuses')
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->get();

        $this->STATUS = json_decode($res);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function index($page)
    {
        if ($page == 0) $page++;
        $response = Curl::to($this->baseUrl . '/orders?page=' . $page)
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->get();

        $res = json_decode($response);
        $data = $res->results;
        $pages = floor($res->total / 10) + 1;

        return view('orders.index')->with([
            'title' => 'List of Orders',
            'data' => $data,
            'current' => $page,
            'pages' => $pages,
            'status' => $this->STATUS
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = Curl::to($this->baseUrl . '/orders/' . $id)
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->get();

        $data = json_decode($res);
        return view('orders.show')->with([
            'data' => $data,
            'status' => $this->STATUS
        ]);
    }

    /**
     * Show the form for search.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('orders.search');
    }

    /**
     * Handle search form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchHandle(Request $request)
    {
        $type = $request['type'];
        $fields = (object) [];

        foreach ($this->FIELDS as $item) {
            $fields->{$item} = ($request[$item]) ? $request[$item] : '*';
        }

        if ($type == 'devices') {
            if ($fields->device_type == '*' && $fields->manufacturer == '*') $criteria = '*';
            if ($fields->device_type == '*' && $fields->manufacturer != '*') $criteria = $fields->manufacturer;
            if ($fields->manufacturer != '*' && $fields->manufacturer == '*') $criteria = $fields->device_type;
            if ($fields->manufacturer != '*' && $fields->manufacturer != '*') $criteria = $fields->manufacturer;
        } else {
            $criteria = $fields->{$type};
        }


        $full = 'full';
        foreach ($fields as $item) {
            if ($item == $criteria) {
                $full = $full.'&*';
                continue;
            };
            $full = $full.'&'.$item;
        }

        return redirect()->route('product.searchResult', [
            'type' => $type,
            'criteria' => $criteria,
            'page' => 1,
            'full' => $full
        ]);
    }

     /**
     * Display a listing of the resource.
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function searchResult($type, $criteria, $page, $full)
    {
        /** Disable search by notes */
        if ($type == 'notes') return redirect()->route('product.search')->with('error', 'Search by "Notes" is currently disabled.');

        /** Get inital result */
        if ($page == 0) $page++;
        $response = Curl::to($this->baseUrl . '/search/' . $type . '?page=' . $page)
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->withData( array(
            'Criteria' => $criteria,
        ))
        ->post();

        /** Handle error */
        if ((str_contains($response, 'error'))) return redirect()->route('product.search')->with('error', json_decode($response)->error);

        /** If response has data -> filter */
        $res = json_decode($response);
        $pages = floor($res->total / 10) + 1;
        $data = $res->results;

        $fields = preg_split('[&]', $full, -1);
        $fieldsProp = ['full', 'deviceType', 'deviceManufacturer', 'status', 'technician', 'notes'];
        $data1 = [];
        $data2 = [];

        /** Expected flow: get result -> filter -> if not enough for 1 page -> get result -> filter -> return */

        while(count($data2) < 10) {
            $page++;

            /** End loop when over maximum page */
            if ($page > $pages) break;
            $response = Curl::to($this->baseUrl . '/search/' . $type . '?page=' . $page)
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->withData( array(
                'Criteria' => $criteria,
            ))
            ->post();
            $data = array_merge($data, json_decode($response)->results);

            /** Filter pattern. In development */
            // for ($j = 1; $j < count($fields) - 1; $j++) {
            //     if ($fields[$j] == '*') continue;
            //     $prop = $fieldsProp[$j];
            //     $keyword = $fields[$j];

            //     for ($i = 0; $i < count($data); $i++) {
            //         if ($data[$i]->{$prop} == $keyword) array_push($data1, $data[$i]);
            //     }
            // }

            /** For assignment only */
            // Filter 'Phone'
            $prop1 = $fieldsProp[1];
            $keyword1 = $fields[1];
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]->{$prop1} == $keyword1) array_push($data1, $data[$i]);
            }

            // Filer 'tecician - required'
            $prop4 = $fieldsProp[4];
            $keyword4 = $fields[4];
            if ($keyword4 == 'required') {
                for ($i = 0; $i < count($data1); $i++) {
                    if ($data1[$i]->{$prop4} != null) array_push($data2, $data1[$i]);
                }
            } else {
                for ($i = 0; $i < count($data1); $i++) {
                    if ($data1[$i]->{$prop4} == $keyword4) array_push($data2, $data1[$i]);
                }
            }
        }

        return view('orders.searchResult')->with([
            'title' => 'Search results',
            'data' => $data2,
            'current' => $page,
            'pages' => $pages,
            'status' => $this->STATUS,
            'type' => $type,
            'criteria' => $criteria,
            'full' => $full,
            'fields' => $fields
        ]);
    }

    /**
     * Show the invoices form.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesSearch()
    {
        return view('orders.invoicesSearch');
    }

    /**
     * Handle the invoices form.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesHandle(Request $request)
    {
        return redirect()->route('product.invoices', [
            'from' => $request['from'],
            'to' => $request['to'],
        ]);
    }

    /**
     * Show the invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoices($from, $to)
    {
        $day = 24*3600;
        $week = 24*3600*7;
        $start = strtotime($from);
        $end = strtotime($to);
        $weeks = ($end - $start) / $week;
        $timeframe = [];

        /** Add weeks */
        for ($i = 0; $i < $weeks; $i++) {
            if ($i == $weeks - 1) {
                /** Last week */
                array_push($timeframe, [
                    date("Y-m-d", $start + $week * $i), $to
                ]);
                break;
            }

            $a = date("Y-m-d", $start + $week  * $i);
            $b = date("Y-m-d", $start + $week * ($i + 1) - $day);
            array_push($timeframe, [$a, $b]);
        }

        $data = [];
        foreach ($timeframe as $time) {
            array_push($data,
                $this->getInvoices($time[0], $time[1])
            );
        }

        return view('orders.invoices')->with(['data' => $data, 'month' => date("F", $start)]);
    }

    public function getInvoices($from , $to) {
        $page = 1;
        $response = Curl::to($this->baseUrl . '/report/' . $from . '/' . $to . '?page=' . $page)
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->post();

        if ((str_contains($response, 'error'))) return (object)[
            'from' => $from,
            'to' => $to,
            'nbrOfInvoice' => 0,
            'totalAmount' => 0,
        ];

        $res = json_decode($response);
        $pages = floor($res->total / 10) + 1;
        $data = $res->results;

        while($page < $pages) {
            $page++;
            $response = Curl::to($this->baseUrl . '/report/' . $from . '/' . $to . '?page=' . $page)
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->post();
            if ((str_contains($response, 'error'))) break;
            $data = array_merge($data, json_decode($response)->results);
        }

        $totalAmount = 0;
        foreach ($data as $item) {
            $totalAmount = $totalAmount + $item->amount;
        }

        return (object)[
            'from' => $from,
            'to' => $to,
            'nbrOfInvoice' => count($data),
            'totalAmount' => $totalAmount,
        ];
    }

    /**
     * Show the create form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Handle the create form.
     *
     * @return \Illuminate\Http\Response
     */
    public function createHandle(Request $request)
    {
        $response = Curl::to($this->baseUrl . '/orders/create')
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->withData( array(
                'DeviceManufacturer' => $request['DeviceManufacturer'],
                'DeviceBrand' => $request['DeviceBrand'],
                'DeviceType' => $request['DeviceType']
            ))
            ->post();
        $res = json_decode($response);

        $response2 = Curl::to($this->baseUrl . '/orders/'. $res->id .'/notes/create')
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->withData( array(
                'Type' => $request['type'],
                'Description' => $request['description']
            ))
            ->post();

        return redirect()->route('product.show', $res->id)->with('success', $res->message);
    }

    /**
     * Create new note for order.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNote(Request $request)
    {
        $response = Curl::to($this->baseUrl . '/orders/'. $request['orderID'] .'/notes/create')
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->withData( array(
                'Type' => $request['type'],
                'Description' => $request['description']
            ))
            ->post();

        $res = json_decode($response);
        // return redirect()->route('product.show', $request['orderID'])->with('success', 'New note created.');
        return $response;
        // Later!!
    }
}
