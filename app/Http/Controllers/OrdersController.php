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
}