@extends('layout')
  
@section('content')
    <div class="container">

        <div class="row" style="margin:20px;">
            <div class="col-15">
                <div class="card">
                    <div class="card-header">
                        <h2>Matchs</h2>
                    </div>
                    <div class="card-body">
                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id='empTable'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>id</th>
                                        <th>commence_time</th>
                                        <th>completed</th>
                                        <th>scores</th>
                                        <th>teams</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($matchs as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->match_id }}</td>
                                        <td>{{ $item->commence_time }}</td>
                                        <td>{{ $item->completed }}</td>
                                        <td>{{ $item->home_score.' : '.$item->away_score  }}</td>
                                        <td>{{ $item->home_team.' vs '.$item->away_team  }}</td>
                                        <td>
                                        <button class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update</a></button>
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