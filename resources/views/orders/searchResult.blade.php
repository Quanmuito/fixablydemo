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
                        <th scope="row">{{$data[$i]->id}}</th>
                        <td>{{ $data[$i]->deviceType }}</td>
                        <td>{{ $data[$i]->deviceManufacturer }}</td>
                        <td>{{ $data[$i]->deviceBrand }}</td>
                        <td>{{ ($data[$i]->technician) ? $data[$i]->technician : 'UNASSIGNED' }}</td>
                        <td>{{ $status[$data[$i]->status - 1]->description }}</td>
                        <td>
                            <a class="btn btn-primary" href={{ route('product.show', $data[$i]->id) }} aria-label="View detail">
                                View detail
                            </a>
                        </td>
                    </tr>
                @endfor
        </tbody>
    </table>
@else
    <h1>No order match found on page {{$current}}, go to next page.</h1>
@endif

    {{-- Pagination --}}
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href={{ route('product.searchResult',[
                    'type' => $type,
                    'criteria' => $criteria,
                    'page' => 1,
                    'full' => $full
                ]) }} aria-label="Previous">
                    <span aria-hidden="true">&laquo;&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href={{ route('product.searchResult',[
                    'type' => $type,
                    'criteria' => $criteria,
                    'page' => $current - 1,
                    'full' => $full
                ]) }} aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href={{ route('product.searchResult',[
                    'type' => $type,
                    'criteria' => $criteria,
                    'page' => $current + 1,
                    'full' => $full
                ]) }} aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href={{ route('product.searchResult',[
                    'type' => $type,
                    'criteria' => $criteria,
                    'page' => $pages,
                    'full' => $full
                ]) }} aria-label="Next">
                    <span aria-hidden="true">&raquo;&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
{{json_encode($fields)}}
@endsection

@include('inc.script')