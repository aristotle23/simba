@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($accounts as $acc)
                                <div class="col">
                                    <h4><small>{{$acc->currency->currency}} : </small><strong>{{round($acc->balance,2)}}</strong></h4>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header h3">{{ __('Transaction') }} <a href="{{route("new")}}" class="btn btn-primary "
                            style="float: right">New Transaction</a></div>

                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Currency</th>
                                    <th scope="col">Created_At</th>
                                    <th scope="col">Updated_At</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <th scope="row">1</th>
                                    <td></td>
                                    <td>Me</td>
                                    <td><span class="text-success">+100</span></td>
                                    <td>USD</td>
                                    <td>2021-10-11 11:00:00</td>
                                    <td>2021-10-11 11:00:00</td>
                                </tr> --}}
                                @foreach ($data as $item)
                                    <tr>
                                        <th scope="row">{{ $item[0] }}</th>
                                        <td>{{ $item[1] }}</td>
                                        <td>{{ $item[2] }}</td>
                                        <td>
                                            <span
                                                class=" @if ($item[7]) text-success @else text-danger @endif">
                                                {{ round($item[3],2) }}
                                            </span>
                                        </td>
                                        <td>{{ $item[4] }}</td>
                                        <td>{{ $item[5] }}</td>
                                        <td>{{ $item[6] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
