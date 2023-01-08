@extends('layout')
  
@section('content')
    <div class="container">
        <div class="row" style="margin:20px;">
            <div class="col-15">
                <div class="card">
                    <div class="card-header">
                        <h2>Actions</h2>
                    </div>
                    <div class="card-body">
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id='empTable'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Mtach / Trasaction</th>
                                        <th>CreatedAt</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['action'] }}</td>
                                        <td>{{ $item['id'] }}</td>
                                        <td>{{ $item['created_at'] }}</td>
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