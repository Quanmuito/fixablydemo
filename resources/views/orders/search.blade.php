@extends('layouts.app')

@section('content')
{!! Form::open(['action' => 'OrdersController@searchHandle', 'method' => 'POST']) !!}
    <div class="mb-3">
        {{ Form::label('type', 'Select a search type', ['class' => 'form-label']) }}
        {{
            Form::select(
                'type',
                ['notes' => 'Notes', 'technicians' => 'Technicians', 'devices' => 'Devices', 'statuses' => 'Statuses'],
                'devices',
                ['class' => 'form-select']
            )
        }}
    </div>
    {{ Form::label('criteria', 'Select criterias', ['class' => 'form-label']) }}
    <div class="mb-3">
        <table class="table table-striped table-hover text-center" id="criteria">
            <thead>
                <tr>
                    <th style="max-width: 20%" scope="col">Device Type</th>
                    <th style="max-width: 20%" scope="col">Manufacturer</th>
                    <th style="max-width: 20%" scope="col">Status</th>
                    <th style="max-width: 20%" scope="col">Technician</th>
                    <th style="max-width: 20%" scope="col">Notes description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{
                            Form::select(
                                'device_type',
                                ['*' => 'All', 'required' => 'Required', 'Laptop' => 'Laptop', 'Tablet' => 'Tablet', 'Phone' => 'Phone'],
                                'Phone',
                                ['class' => 'form-select']
                            )
                        }}
                    </td>
                    <td>
                        {{
                            Form::select(
                                'manufacturer',
                                ['*' => 'All', 'required' => 'Required', 'Sony' => 'Sony', 'Samsung' => 'Samsung', 'Apple' => 'Apple'],
                                'Apple',
                                ['class' => 'form-select']
                            )
                        }}
                    </td>
                    <td>{{
                            Form::select(
                                'statuses',
                                ['*' => 'All', 'required' => 'Required', 'Open' => 'Open', 'Closed' => 'Closed', 'Assigned' => 'Assigned', 'Unpaid' => 'Unpaid'],
                                '*',
                                ['class' => 'form-select']
                            )
                        }}
                    </td>
                    <td>
                        {{
                           Form::text('technicians', 'required', ['class' => 'form-control', 'placeholder' => 'Name of a technician'])
                        }}
                    </td>
                    <td>
                        {{
                           Form::text('notes', '', ['class' => 'form-control', 'placeholder' => 'Note description'])
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{ Form::submit('Search', ['class' => 'btn btn-primary']) }}
{!! Form::close() !!}
@endsection