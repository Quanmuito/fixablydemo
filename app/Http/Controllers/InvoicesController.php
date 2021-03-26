<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class InvoicesController extends Controller
{
    protected $TOKEN;
    protected $baseUrl = 'https://careers-api.fixably.com';
    protected $STATUS;

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
     * Show the INVOICES form.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoices()
    {
        return view('invoices.invoices');
    }

    /**
     * Handle the INVOICES form.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesHandle(Request $request)
    {
        return redirect()->route('invoices.invoicesResult', [
            'from' => $request['from'],
            'to' => $request['to'],
        ]);
    }

    /**
     * Show the invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoicesResult($from, $to)
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

        return view('invoices.invoicesResult')->with(['data' => $data, 'month' => date("F", $start)]);
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
}