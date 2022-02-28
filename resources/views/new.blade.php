@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header h3">{{ __('New Transaction') }} <a href="{{route("home")}}" class="btn btn-primary "
                        style="float: right">Transactions</a></div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session("error") }}
                            </div>
                        @else
                            @if (session("msg"))
                                <div class="alert alert-success" role="alert">
                                    {{ session("msg") }}
                                </div>
                            @endif
                        @endif

                        <form method="POST" action="{{ route('new.transaction') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-3 offset-4">

                                    <div class="form-group text-center">
                                        <label for="exampleInputEmail1">Your Currency</label>
                                        <select
                                            class="form-control currency text-center @error('source_currency') is-invalid @enderror"
                                            name="source_currency" id="" required data-type="source" >

                                            @foreach ($currencies as $item)
                                                <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                            @endforeach
                                        </select>
                                       
                                    </div>

                                </div>
                                <div class="col-3">
                                    <div class="form-group text-center">
                                        <label for="exampleInputEmail1">Recipient Currency</label>
                                        <select
                                            class="form-control text-center currency @error('target_currency') is-invalid @enderror"
                                            name="target_currency" required required data-type="target" >

                                            @foreach ($currencies as $item)
                                                <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="name" class="col-3 col-form-label text-md-end">Recipient</label>

                                <div class="col-8">
                                    <select name="recipient" id="" class="form-control" required>
                                        <option value="" selected disabled>Please select..</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <label for="source_amount" class="col-3 col-form-label text-md-end">You're Sending</label>

                                <div class="col-8">
                                    <input id="source_amount" type="number"
                                        class="form-control @error('source_amount') is-invalid @enderror"
                                        name="source_amount" value="{{ old('source_amount') }}" required
                                        autocomplete="off" autofocus>

                                    @error('source_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                            
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Transfer') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
