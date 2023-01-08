@extends('layout')
  
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <img src="https://ui-avatars.com/api/?rounded=true&background=random" alt="Profile">
                    Your Wallet is: {{ Auth::user()->eth_address }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection