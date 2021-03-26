@extends('layouts.app')

@section('content')
{!! Form::open(['action' => 'InvoicesController@invoicesHandle', 'method' => 'POST']) !!}
    <div class="mb-3">
        {{ Form::label('from', 'Input start day', ['class' => 'form-label']) }}
        {{
            Form::text('from', '2020-11-01', ['class' => 'form-control', 'placeholder' => 'Format: YYYY-MM-DD'])
        }}
    </div>
    <div class="mb-3">
        {{ Form::label('to', 'Input end day', ['class' => 'form-label']) }}
        {{
            Form::text('to', '2020-11-30', ['class' => 'form-control', 'placeholder' => 'Format: YYYY-MM-DD'])
        }}
    </div>
    {{ Form::submit('Get report', ['class' => 'btn btn-primary']) }}
{!! Form::close() !!}
@endsection