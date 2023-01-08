@extends('layout')
  
@section('content')
    <div class="container">

          
        <br/>
        <br/>
        <form  method="GET" action="{{ route('user.redeem4', 'zz') }}" >
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputEmail4">Eth Address</label>
                <input type="text" class="form-control" id="eth_address" name="eth_address"  placeholder="Eth Address">
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4">Chips</label>
                <input type="number" class="form-control" id="chips" name="chips"  placeholder="100 Chips">
                </div> 
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Send gift</a></button>
        </form>
        <br/>
        <br/>
        <form  method="GET" action="{{ route('user.redeemVip', 'zz') }}" >
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="inputEmail4">Eth Address</label>
                <input type="text" class="form-control" id="wallets" name="wallets"  placeholder="wallet1,wallet2,wallet3...">
                </div>
                <div class="form-group col-md-6">
                <label for="inputPassword4">Gift Chips</label>
                <input type="number" class="form-control" id="chips" name="chips"  placeholder="100 Chips">
                </div> 
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Unlock Vip</a></button>
        </form>
        <br/>
        <br/>

        <div class="row" style="margin:10px;">
            <div class="col-18">
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
                                        <th>Name</th>
                                        <th>User Eth Address</th>
                                        <th>Balance</th>
                                        <th>Role</th>
                                        <th>CreatedAt</th>
                                        <th>-</th>
                                        <th>Actions</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->eth_address }}</td>
                                        <td>{{ $item->balance }}</td>
                                        <td>{{ $item->role }}</td>
                                        <td>{{ $item->created_at }}</td>
  
                                        <td>
                                        <button class="btn btn-success btn-sm"><a style="color:white" href="{{ route('user.upgrade', $item->eth_address) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upgrade</a></button>
                                        </td> 
                                        <td>
                                        <button class="btn btn-info btn-sm"><a style="color:white" href="{{ route('user.actions', $item->eth_address) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Detail</a></button>
                                        </td> 
                                        <td>
                                        <button class="btn btn-danger btn-sm"><a style="color:white" href="{{ route('user.block', $item->eth_address) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Block</a></button>    
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