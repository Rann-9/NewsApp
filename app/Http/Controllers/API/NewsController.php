<?php

namespace App\Http\Controllers\API;

use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class NewsController extends Controller
{
    public function index()
    {
        try {
            $news = News::latest()->get();
            return ResponseFormatter::success(
                $news,
                'Data list of news'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function show($id)
    {
        try {
            // get data by id
            $news = News::findOrFail($id);
            return ResponseFormatter::success(
                $news,
                'Data news by id'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // validate
            $this->validate($request, [
                'title' => 'required|min:1|max:100',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
            ]);

            // store image
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());

            // store data
            $news = News::create([
                'title' => $request->title,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'image' => $image->hashName(),
                'slug' => Str::slug($request->title)
            ]);

            return ResponseFormatter::success(
                $news,
                'Data News Berhasil Ditambahkan'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // validate
            $this->validate($request, [
                'title' => 'required|max:100',
                'category_id' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120'
            ]);

            // get data by id
            $news = News::findOrFail($id);

            // store image
            if ($request->file('image') == '') {
                $news->update([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'category_id' => $request->category_id,
                    'content' => $request->content
                ]);
            } else {
                // jika gambar ingin diupdate, hapus image lama
                Storage::disk('local')->delete('public/news/' . basename($news->image));

                //upload image baru
                $image = $request->file('image');
                $image->storeAs('public/news/', $image->hashName());

                // update data
                $news->update([
                    'title' => $request->title,
                    'category_id' => $request->category_id,
                    'slug' => Str::slug($request->category),
                    'image' => $image->hashName(),
                    'content' => $request->content
                ]);
            }

            return ResponseFormatter::success(
                $news,
                'Data News Berhasil Diupdate'
            );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function destroy($id)
    {
        try {
            // get data by id
        $news =  News::findOrFail($id);

        // delete image
        Storage::disk('local')->delete('public/news/' . basename($news->image));

        // delete data
        $news->delete();

        return ResponseFormatter::success(
            null,
            'Data News Berhasil Dihapus'
        );
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }
}
