@extends('frontend.parent')

@section('content')
    <section class="single-post-content">
        <div class="container">
            <div class="row">

                <div class="col-md-9 post-content" data-aos="fade-up">
                    <div class="post-meta"><span class="date">{{ $news->category->name }}</span> <span
                            class="mx-1">&bullet;</span> <span>{{ $news->created_at->diffForHumans() }}</span></div>
                    <h1 class="mb-5">{{ $news->title }}</h1>
                    <img src="{{ $news->image }}" alt="" class="img-fluid">
                    <p>
                        {{ $news->content }}
                    </p>
                </div>

                <div class="col-md-3">
                    @foreach ($sideNews as $news)
                        <div class="post-entry-1 border-bottom">
                            <div class="post-meta"><span class="date">{{ $news->name }}</span> <span
                                    class="mx-1">&bullet;</span>
                                <span>{{ $news->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="mb-2"><a href="{{ route('detailNews', $news->slug) }}">
                                    {{-- limit Character --}}
                                    {{ Str::limit($news->title, 30) }}</a></h2>
                            <span class="author mb-3 d-block">Admin</span>
                            <p>{{ Str::limit(strip_tags($news->content, 70)) }}</p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
@endsection
