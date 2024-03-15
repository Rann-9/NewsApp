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

                                        <a href="{{ route('news.show', $row->id) }}">
                                            <button class="btn btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('news.edit', $row->id) }}">
                                            <button class="btn btn-warning text-white">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('news.destroy', $row->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Yakin Mau Dihapus?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
