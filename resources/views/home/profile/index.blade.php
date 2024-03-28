@extends('home.parent')

@section('content')
    <div class="row">
        <div class="col-md-6 d-flex justify-content-center">
            @if (empty(Auth::user()->profile->image))
                <img src="https://ui-avatars.com/api/color=fffff?name={{ Auth::user()->name }}" alt="Ini Istilah User"
                    class="w-25">
            @else
                <img src="{{ Auth::user()->profile->image }}" alt="ini profile image">
            @endif

        </div>
        <div class="col-md-6 text-center">
            <h3>Profile</h3>
            <ul class="list-group">
                <li class="list-group-item" aria-current="true">Name Account : <strong>{{ Auth::user()->name }}</strong></li>
                <li class="list-group-item">Email Account : <strong>{{ Auth::user()->email }}</strong></li>
                <li class="list-group-item">First Name : <strong>{{ Auth::user()->profile->first_name }}</strong></li>
                <li class="list-group-item">Role Acount : <strong>{{ Auth::user()->role }}</strong< /li>
            </ul><!-- End ist group with active and disabled items -->
            @if (empty(Auth::user()->profile->image))
                <a href="{{ route('createProfile') }}" class="btn btn-info text-white mt-2">
                    <i class="bi bi-plus"></i>
                    Create Profile
                </a>
            @else
                <a href="{{ route('editProfile') }}" class="btn btn-warning text-white mt-2">
                    <i class="bi bi-pencil"></i>
                    Edit Profile
                </a>
            @endif
        </div>
    </div>
@endsection
