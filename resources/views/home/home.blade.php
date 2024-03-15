@extends('home.parent')

@section('content')
    <div class="container">
        <div class="row card p-4">
            <h1>
                Welcome {{ Auth::user()->name }}
            </h1>
            <hr>
            <div class="card p-4">
                <!-- List group with active and disabled items -->
                <h3 class="text-center">Detail Account</h3>
              <ul class="list-group">
                <li class="list-group-item" aria-current="true">Name Account : <strong>{{ Auth::user()->name }}</strong></li>
                <li class="list-group-item">Email Account : <strong>{{ Auth::user()->email }}</strong></li>
                <li class="list-group-item">Role Acount : <strong>{{ Auth::user()->role }}</strong</li>
              </ul><!-- End ist group with active and disabled items -->
            </div>
        </div>
    </div>
@endsection