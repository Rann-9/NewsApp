@extends('home.parent')

@section('content')
    <div class="row">
        <div class="card p-4">
            <h3 class="card-title">
                Create Profile {{ Auth::user()->name }}
            </h3>
            <form action="{{ route('storeProfile') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="col mt-3 mb-3">
                    <label for="" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name">
                </div>

                <div class="col mt-3 mb-3">
                    <label for="" class="form-label">Image Profile</label>
                    <input type="file" class="form-control" name="image">
                </div>

                <button type="submit" class="btn btn-info text-white">
                    <i class="bi bi-plus"></i>
                    Create Profile
                </button>
            </form>
        </div>
    </div>
@endsection
