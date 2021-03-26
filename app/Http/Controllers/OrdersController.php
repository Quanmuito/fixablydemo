<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class OrdersController extends Controller
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
     * Display a listing of ORDERS.
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
     * Display the specified ORDERS.
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
     * Show the form for SEARCH.
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
            if ($fields->device_type != '*' && $fields->manufacturer == '*') $criteria = $fields->device_type;
            if ($fields->device_type != '*' && $fields->manufacturer != '*') $criteria = $fields->manufacturer;
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

        return redirect()->route('orders.searchResult', [
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
        if ($type == 'notes') return redirect()->route('orders.search')->with('error', 'Search by "Notes" is currently disabled.');

        /** Get inital result */
        if ($page == 0) $page++;
        $response = Curl::to($this->baseUrl . '/search/' . $type . '?page=' . $page)
        ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
        ->withData( array(
            'Criteria' => $criteria,
        ))
        ->post();

        /** Handle error */
        if ((str_contains($response, 'error'))) return redirect()->route('orders.search')->with('error', json_decode($response)->error);

        /** If response has data -> filter */
        $res = json_decode($response);
        $pages = floor($res->total / 10) + 1;
        $data = $res->results;

        /** Prepare for filter */
        $fields = preg_split('[&]', $full, -1);
        $fieldsProp = ['full', 'deviceType', 'deviceManufacturer', 'status', 'technician', 'notes'];
        $data_filter = [[], [], [], [], [], []];

        /** Expected flow: get result -> filter -> if not enough for 1 page -> get result -> filter -> return */

        while(count($data_filter[4]) < 10) {
            $page++;

            /** End loop when over maximum page */
            if ($page > $pages) break;

            /** Keep getting data */
            $response = Curl::to($this->baseUrl . '/search/' . $type . '?page=' . $page)
            ->withHeaders( array( 'X-Fixably-Token' => $this->TOKEN ) )
            ->withData( array(
                'Criteria' => $criteria,
            ))
            ->post();

            /**  If error -> break, otherwise continue */
            if ((str_contains($response, 'error'))) break;
            $data = array_merge($data, json_decode($response)->results);
            $data_filter[0] = $data;

            /** Filter */
            for ($j = 1; $j < count($fields) - 1; $j++) {
                if ($fields[$j] == '*') {
                    $data_filter[$j] = $data_filter[$j - 1];
                    continue;
                };

                $prop = $fieldsProp[$j];
                $keyword = $fields[$j];
                $arr = $data_filter[$j - 1];

                if ($keyword == 'required') {
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($arr[$i]->{$prop} != null) array_push($data_filter[$j], $arr[$i]);
                    }
                } else {
                    for ($i = 0; $i < count($arr); $i++) {
                        if ($arr[$i]->{$prop} == $keyword) array_push($data_filter[$j], $arr[$i]);
                    }
                }
            }
        }

        return view('orders.searchResult')->with([
            'title' => 'Search results',
            'data' => $data_filter[4], // disabled for sort by notes, otherwise return [5]
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
     * Show the CREATE form.
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
        $res2 = json_decode($response2);

        return redirect()->route('orders.show', $res->id)->with('success', $res->message.". ".$res2->message);
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
        // return redirect()->route('orders.show', $request['orderID'])->with('success', 'New note created.');
        return $response;
        // Not working for now !!
    }
}