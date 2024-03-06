<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // title halaman index
        $title = 'Category - Index';
        // mengurutkan data berdasarkan data terbaru
        $category = Category::latest()->get();
        return view('home.category.index', compact(
            'category', 
            'title'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Category - Index';
        return view('home.category.create', compact(
            'title'
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
        $this->validate($request, [
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // melakukan upload image
        $image = $request->file('image');

        // menyimpan image yang diupload ke folder
        // storage/app/public/vategory
        // fungsi getClientOriginalName itu menggunakan nama asli dari image
        $image->storeAs('public/category', $image->getClientOriginalName());

        // melakukan save to database
        if (
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $image->getClientOriginalName()
            ])
        ) {
            return redirect()->route('category.index')->with('success', 'Category Berhasil Ditambahkan');
        } else {
            return redirect()->route('category.create')->with('errors', 'Category Gagal Ditambahkan');
        }


        // melakukan return redirect

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
        $title = 'Category - Edit';
        $category = Category::find($id);
        return view('home.category.edit', compact (
            'category',
            'title'
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
            'name' => 'required|max:100',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // get data by id
        $category = Category::find($id);
        
        // jika image kosong (tidak ingin di update)

        if ($request->file('image') == '') {
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            return redirect()->route('category.index');
        } else {
            // jika gambar ingin diupdate, hapus image lama
            Storage::disk('local')->delete('public/category/'. basename($category->image));

            //upload image baru
            $image = $request->file('image');
            $image->storeAs('public/category/', $image->getClientOriginalName());

            // update data
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'image' => $image->getClientOriginalName()
            ]);

            return redirect()->route('category.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get data by id
        $category =  Category::findOrFail($id);
        
        // delete image
        // basename berfungsi untuk mengambil nama file
        Storage::disk('local')->delete('public/category/' . basename($category->image));

        // delete data by id
        if (
            $category->delete()
        ) {
            return redirect()->route('category.index')->with('success', 'Category Berhasil Dihapus');
        } else {
            return redirect()->route('category.destroy')->with('errors', 'Category Gagal Dihapus');
        }
        
    }
}
