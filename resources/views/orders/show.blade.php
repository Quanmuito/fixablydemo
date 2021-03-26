@extends('layouts.app')

@section('content')
<br>
<br>
<h1>Order detail</h1>
<br>
<br>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Order ID</th>
            <th scope="col">Device Type</th>
            <th scope="col">Manufacturer</th>
            <th scope="col">Brand</th>
            <th scope="col">Technician</th>
            <th scope="col">Invoices</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{ $data->id }}</th>
            <td>{{ $data->deviceType }}</td>
            <td>{{ $data->deviceManufacturer }}</td>
            <td>{{ $data->deviceBrand }}</td>
            <td>{{ ($data->technician) ? $data->technician : 'UNASSIGNED' }}</td>
            <td>{{ ($data->invoices) ? $data->invoices->amount : 'NO INVOICE' }}</td>
        </tr>
    </tbody>
</table>
<div class="card-container">
    @foreach ($data->notes as $item)
        <div class="card" style="width: 100%">
            <div class="card-header">
                NOTES
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $item->type }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Description</h6>
                <p class="card-text">{{ $item->description }}</p>
            </div>
            <div class="card-footer text-muted">
               {{ $item->created }}
            </div>
        </div>
    @endforeach
</div>

<div class="container">
    <br>
    <br>
    <!-- Button to Open the Modal - Disabled -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Add new note
    </button>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Order ID: {{ $data->id }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    {!! Form::open(['action' => 'ProductsController@createNote', 'method' => 'POST']) !!}
                        <div class="mb-3">
                            {{ Form::label('type', 'Select a type', ['class' => 'form-label']) }}
                            {{
                                Form::select(
                                    'type',
                                    ['Issue' => 'Issue', 'Diagnosis' => 'Diagnosis', 'Resolution' => 'Resolution'],
                                    'Issue',
                                    ['class' => 'form-select']
                                )
                            }}
                        </div>
                        <div class="mb-3">
                            {{ Form::label('description', 'Insert description', ['class' => 'form-label']) }}
                            {{
                                Form::text('description', 'Broken screen', ['class' => 'form-control', 'placeholder' => 'Description'])
                            }}
                        </div>
                        <input type="hidden" id="orderID" name="orderID" value={{$data->id}}>
                        {{ Form::submit('Create', ['class' => 'btn btn-primary', 'disabled' => 'disabled']) }}
                    {!! Form::close() !!}
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection