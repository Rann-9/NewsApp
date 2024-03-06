<?php

namespace App\Http\Controllers\Admin;



use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // title untuk memberikan judul halaman
        $title = 'Index News';

        // get data terbaru dari table news/dari model news
        $news = News::latest()->paginate(5);
        $category = Category::all();
        // compact berfungsi untuk mengirimkan data ke views
        return view('home.news.index', compact(
            'title',
            'news',
            'category'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create News';
        $category = Category::all();
        return view('home.news.create', compact('title', 'news', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $this->validate($request, [
            'title' => 'required|min:1|max:100',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:3999'
        ]);
        // upload img
        $image = $request->file('image');
        //kedalam folder public/news
        // fungsi hasName bikin random nama file
        $image->storeAs('public/news', $image->hashName());
        //create data kedalam table news
        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $image->hashName(),
            'slug' => Str::slug($request->title),
        ]);
        return redirect()->route('news.index')->with('success', 'Mantap Berita Berhasil Di Tambahkan! 👍');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}