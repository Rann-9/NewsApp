@extends('home.parent')

@section('content')

<div class="row">
    <div class="card p-4">
        <h3>News</h3>
        <a href="{{ route('news.create') }}" class="btn btn-primary">
            <i class="bi bi-plus">Create News</i>
        </a>
        <div class="container mt-3">
            <div class="card p-3">
                <h5 class="card-title">Data News</h5>
                <table class="table datatable">
                    <thead>
                        <tr>
                            <td>Nomor</td>
                            <td>Title</td>
                            <td>Category</td>
                            <td>Image News</td>
                            <td>Image Category</td>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->title }}</td>
                            <td>{{ $row->category->name }}</td>
                            <td><img src="{{ $row->image }}" width="100px" alt="image"></td>
                            <td><img src="{{ $row->category->image }}" width="100px" alt="Ini image category"></td>
                            <td>
                                <button class="btn btn-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                            <p class="text-center">Data masih kosong</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
    
</div>

@endsection