@extends('home.parent')

@section('content')
    {{-- //alert success --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- //alert success --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="card p-4">
            <h3 class="card-title">Change Password</h3>
            <form action="{{ route('profile.update-password') }}" method="post">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Current Password</label>
                    <div class="col-sm-10">
                        <input name="current_password" type="password" class="form-control" placeholder="Current Password">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
                    <div class="col-sm-10">
                        <input name="password" type="password" class="form-control" placeholder="New Password">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Confirmation Password</label>
                    <div class="col-sm-10">
                        <input name="confirmation_password" type="password" class="form-control"
                            placeholder="Confirmation Password">
                    </div>
                </div>
                    <button class="btn btn-primary w-100" type="submit">Change Password</button>
            </form>
        </div>
    </div>
@endsection
