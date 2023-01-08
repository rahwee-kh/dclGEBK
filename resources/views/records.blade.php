@extends('layout')
  
@section('content')
    <div class="container">
        <div class="row" style="margin:20px;">
            <div class="col-15">
                <div class="card">
                    <div class="card-header">
                        <h2>Cash Out Records</h2>
                    </div>
                    <div class="card-body">
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>RecordId</th>
                                        <th>User Eth Address</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>CreatedAt</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->eth_address }}</td>
                                        <td>{{ $item->balance }}</td>
                                        <td>{{ $item->status == '0'?'review':'approved' }}</td>
                                        <td>{{ $item->created_at }}</td>
  
                                        <td>
                                        <button class="btn btn-success btn-sm"><a style="color:white" href="{{ route('user.approve', $item->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Approve</a></button>
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