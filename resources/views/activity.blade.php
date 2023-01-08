@extends('layout')
  
@section('content')
    <div class="container">


        <div class="row" style="margin:20px;">
            <div class="col-15">
                <div class="card">
                    <div class="card-header">
                        <h2>Users</h2>
                    </div>
                    <div class="card-body">
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id='empTable'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>UserId</th>
                                        <th>User Eth Address</th>
                                        <th>Balance</th>
                                        <th>Role</th>
                                        <th>UpdatedAt</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->eth_address }}</td>
                                        <td>{{ $item->balance }}</td>
                                        <td>{{ $item->role }}</td>
                                        <td>{{ $item->updated_at }}</td>
  
                                        <td>
                                        <button class="btn btn-success btn-sm"><a style="color:white" href="{{ route('user.upgrade', $item->eth_address) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upgrade</a></button>
                                        <button class="btn btn-info btn-sm"><a style="color:white" href="{{ route('user.actions', $item->eth_address) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Detail</a></button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection