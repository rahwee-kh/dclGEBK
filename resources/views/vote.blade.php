@extends('layout')
  
@section('content')
    <div class="container">
        <div class="row" style="margin:20px;">
            <div class="col-15">
                <div class="card">
                    <div class="card-header">
                        <h2>Vote</h2>
                    </div>
                    <div class="card-body">
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id='empTable'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Eth Id</th>
                                        <th>User Name</th>
                                        <th>Team</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['eth_address'] }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['team'] }}</td>
                                        <td>
                                        <button class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> View</a></button>
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