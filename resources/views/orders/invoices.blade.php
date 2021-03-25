@extends('layouts.app')

@section('content')
    <br>
    <br>
    <h1>Monthly report - {{$month}}</h1>
    <br>
    <br>
    <table class="table table-striped text-center">
    <thead>
        <tr>
            <th scope="col">Week</th>
            <th scope="col">From</th>
            <th scope="col">To</th>
            <th scope="col">Total Invoices</th>
            <th scope="col">Increase (%)</th>
            <th scope="col">Total Invoiced amount</th>
            <th scope="col">Increase (%)</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < count($data); $i++)
            <tr>
                <th scope="row">{{ $i + 1 }}</th>
                <td>{{ $data[$i]->from }}</td>
                <td>{{ $data[$i]->to }}</td>
                <td>{{ $data[$i]->nbrOfInvoice }}</td>
                <td>
                    {{
                        ($i == 0)
                        ? 0
                        : number_format(($data[$i]->nbrOfInvoice - $data[$i - 1]->nbrOfInvoice) * 100 / $data[$i - 1]->nbrOfInvoice, 1)
                    }}%
                </td>
                <td>{{ $data[$i]->totalAmount }}</td>
                <td>
                    {{
                        ($i == 0)
                        ? 0
                        : number_format(($data[$i]->totalAmount - $data[$i - 1]->totalAmount) * 100 / $data[$i - 1]->totalAmount, 1)
                    }}%
                </td>
            </tr>
        @endfor
    </tbody>
</table>
@endsection