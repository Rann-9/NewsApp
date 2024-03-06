@extends('home.parent')

@section('content')
    <div class="row">
        <div class="card p-4">
            <h3>News Create</h3>
            <form action="{{ route('news.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="mb-2">
                    <label for="inputTitle" class="form-label">News title</label>
                    <input type="text" class="form-control" id="inputTitle" name="title" value="{{ old('title') }}">

                    <label for="inputImage" class="form-label">News Image</label>
                    <input type="file" class="form-control" id="inputImage" name="image" value="{{ old('title') }}">
                </div>
                <label class="col-sm-2 col-form-label">Content Select</label>

                <div class="cols">
                    <select name="category_id" class="form-select" aria-label="Default select example">
                        <option selected>===== Choose Category =====</option>
                        @foreach ( $category as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                        
                    </select>
                </div>
                <div class="mb-2">
                    <label for="inputTitle" class="form-label">Content News</label>
                    <textarea id="editor" name="content"></textarea>
                </div>


                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-plus"></i>
                        Create News
                    </button>
                </div>

                <script>
                    ClassicEditor
                        .create(document.querySelector('#editor'))
                        .then(editor => {
                            console.log(editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });
                </script>
            </form>
        </div>
    </div>
@endsection