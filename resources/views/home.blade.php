@extends('home.parent')

@section('content')
    <div class="container">
        <div class="row card p-4">
            <h1>Welcome {{ Auth::user()->name }}</h1>

            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn btn-danger">
                    Log out
                </button>
            </form>
        </div>
    </div>
@endsection
