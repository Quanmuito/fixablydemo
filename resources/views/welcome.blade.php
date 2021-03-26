@extends('layouts.app')

@section('content')
    <h1>Demo for Fixably assignment</h1>
    <br>
    <br>
    <div class="list-group">
        <div class="list-group-item list-group-item-action" aria-current="true">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">All Orders</h5>
                <small>Paginated</small>
            </div>
            <br>
            <ul>
                <li><p class="mb-1">List all orders.</p></li>
                <li>
                    <p class="mb-1">
                        Click on <button class="btn btn-success">Sort</button> to sort by each criteria.
                    </p>
                </li>
                <li>
                    <p class="mb-1">
                        Click on <button class="btn btn-primary">View detail</button> to view details of an order.
                    </p>
                </li>
            </ul>
        </div>
        <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Search</h5>
                <small>Form</small>
            </div>
            <br>
            <ul>
                <li><p class="mb-1">Show a form to search orders by it's criteria.</p></li>
                <li>
                    <p class="mb-1">By default, the form is filled to get result for the requirement:</p>
                    <small class="text-muted">Create a page or dump that lists all orders with an iPhone device and currently assigned to a technician.</small>
                </li>
                <li>
                    <p class="mb-1">
                        Click on <button class="btn btn-primary">Search</button> to start seaching.
                    </p>
                </li>
            </ul>
            <small class="text-muted">Currently, search by "Notes" is not available.</small>
        </div>
        <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">Create</h5>
                <small>Form</small>
            </div>
            <br>
            <ul>
                <li><p class="mb-1">Show a form to create new order.</p></li>
                <li>
                    <p class="mb-1">By default, the form is filled to get result for the requirement:</p>
                    <small class="text-muted">Create a new order for a MacBook Pro and with the defect of Broken screen.</small>
                </li>
                <li>
                    <p class="mb-1">
                        Click on <button class="btn btn-primary">Create</button> to create new order.
                    </p>
                </li>
            </ul>
            <small class="text-muted">Currently, only 1 note can be added when creating a new order (should be multiple).</small>
        </div>
       <div class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">List invoices</h5>
                <small>Form</small>
            </div>
            <br>
            <ul>
                <li><p class="mb-1">Show a form to find invoices within period of time.</p></li>
                <li>
                    <p class="mb-1">By default, the form is filled to get result for the requirement:</p>
                    <small class="text-muted">List each unique week of November 2020. Each week should list the increase or decrease of the above values in percentage with a single decimal from the previous week.</small>
                </li>
                <li>
                    <p class="mb-1">
                        Click on <button class="btn btn-primary">Get report</button> to get the report of invoices.
                    </p>
                </li>
            </ul>
        </div>
    </div>
@endsection