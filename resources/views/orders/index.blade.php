@extends('layouts.app')

@section('content')
<br>
<br>
@if (count($data) > 0)
    <h1>{{ $title }}</h1>
    <br>
    <br>
    <table class="table table-striped text-center" id="myTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Type</th>
                <th scope="col">Manufacturer</th>
                <th scope="col">Brand</th>
                <th scope="col">Technician</th>
                <th scope="col">Status</th>
                <th scope="col">View product</th>
            </tr>
            <tr>
                <th scope="col">#</th>
                @for ($i = 0; $i < 5; $i++)
                    <th scope="col" onclick="sortTable({{$i}})">
                        <button class="btn btn-success" style="width: 100%">Sort</button>
                    </th>
                @endfor
                <th></th>
            </tr>
        </thead>
        <tbody>
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        <th scope="row">{{$i + 1}}</th>
                        <td>{{ $data[$i]->deviceType }}</td>
                        <td>{{ $data[$i]->deviceManufacturer }}</td>
                        <td>{{ $data[$i]->deviceBrand }}</td>
                        <td>{{ ($data[$i]->technician) ? $data[$i]->technician : 'UNASSIGNED' }}</td>
                        <td>{{ $status[$data[$i]->status - 1]->description }}</td>
                        <td>
                            <a class="btn btn-primary" href={{ route('orders.show', $data[$i]->id) }} aria-label="View detail">
                                View detail
                            </a>
                        </td>
                    </tr>
                @endfor
        </tbody>
    </table>

    {{-- Pagination --}}
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href={{ route('orders.index', 1) }} aria-label="Previous">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href={{ route('orders.index', $current - 1) }} aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @for ($i = 0; $i < 10; $i++)
                <li class="page-item">
                    <a class="page-link" href={{ route('orders.index', $current + $i) }}>{{$current + $i}}</a>
                </li>
            @endfor

            <li class="page-item">
                <a class="page-link" href={{ route('orders.index', $current + 1) }} aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href={{ route('orders.index', $pages) }} aria-label="Next">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
@else
    <h1>No order found</h1>
@endif
@endsection

@include('inc.script')