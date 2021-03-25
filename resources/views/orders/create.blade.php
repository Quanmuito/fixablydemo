@extends('layouts.app')

@section('content')
<div class="container">
    {!! Form::open(['action' => 'ProductsController@createHandle', 'method' => 'POST']) !!}
        <br>
        <h3 class="text-center">Order Detail</h3>
        <div class="mb-3">
            {{ Form::label('DeviceType', 'Select a type', ['class' => 'form-label']) }}
            {{
                Form::select(
                    'DeviceType',
                    ['Laptop' => 'Laptop', 'Phone' => 'Phone', 'Tablet' => 'Tablet'],
                    'Laptop',
                    ['class' => 'form-select']
                )
            }}
        </div>
        <div class="mb-3">
            {{ Form::label('DeviceBrand', 'Brand', ['class' => 'form-label']) }}
            {{ Form::text('DeviceBrand', 'MacBook Pro', ['class' => 'form-control', 'placeholder' => 'Brand name']) }}
        </div>
        <div class="mb-3">
            {{ Form::label('DeviceManufacturer', 'Manufacturer', ['class' => 'form-label']) }}
            {{ Form::text('DeviceManufacturer', 'Apple', ['class' => 'form-control', 'placeholder' => 'Manufacturer name']) }}
        </div>

        <h3 class="text-center">Note</h3>
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
        {{ Form::submit('Create new order', ['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
</div>
@endsection