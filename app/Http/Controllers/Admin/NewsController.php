<?php

namespace App\Http\Controllers\Admin;



use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
        $title = 'News - Index';

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
        $title = 'News - Create';
        $category = Category::all();
        return view('home.news.create', compact(
            'title',
            'category'
        ));
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
        if (
            News::create([
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'image' => $image->hashName(),
                'slug' => Str::slug($request->title),
            ])
        ) {
            return redirect()->route('news.index')->with('success', 'Berita Berhasil Di Tambahkan');
        } else {
            return redirect()->route('news.index')->with('errors', 'Berita Gagal Di Tambahkan');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'News - Show';
        // get data by id using model News
        // fungsi dari finOrFail adalah jika data tidak ditemukan maka akan menampilkan eror 404
        $news = News::findOrFail($id);
        return view('home.news.show', compact(
            'title',
            'news'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get data by id
        $title = 'News - Edit';
        $news = News::findOrFail($id);
        $category = Category::all();
        return view('home.news.edit', compact(
            'title',
            'category',
            'news'
        ));

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
        $this->validate($request,[
            'title' => 'required|max:255',
            'category_id' => 'required',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:5120' 
        ]);

        $news = News::findOrFail($id);
        if ($request->file('image') == "") {
            // update data
            $news->update([
                'title' => $request->title,
                'slug' => Str::slug($request->category),
                'category_id' => $request->category_id,
                'content' => $request->content  
            ]);
        } else {
            // hapus old image
            Storage::disk('local')->delete('public/news' .basename($news->image));

            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());

            // update data
            $news->update([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'slug' => Str::slug($request->category),
                'image' => $image->hashName(),
                'content' => $request->content
            ]);
        }

        return redirect()->route('news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // delete image
        Storage::disk('local')->delete('public/news' . basename($news->image));
        // delete data
        $news->delete();

        return redirect()->route('news.index');
    }
}