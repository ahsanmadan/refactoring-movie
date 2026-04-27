<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Category;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index()
    {

        $query = Movie::latest();
        if (request('search')) {
            $query->where('judul', 'like', '%'.request('search').'%')
                ->orWhere('sinopsis', 'like', '%'.request('search').'%');
        }
        $movies = $query->paginate(6)->withQueryString();

        return view('homepage', compact('movies'));
    }

    public function detail($id)
    {
        $movie = Movie::find($id);

        return view('detail', compact('movie'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('input', compact('categories'));
    }

    public function store(StoreMovieRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto_sampul')) {
            $validated['foto_sampul'] = $request->file('foto_sampul')->store('movie_covers', 'public');
        }

        Movie::create($validated);

        return redirect('/')->with('success', 'Film berhasil ditambahkan.');
    }

    public function data()
    {
        $movies = Movie::latest()->paginate(10);

        return view('data-movies', compact('movies'));
    }

    public function form_edit($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all();

        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect("/movies/edit/{$id}")
                ->withErrors($validator)
                ->withInput();
        }

        $movie = Movie::findOrFail($id);

        if ($request->hasFile('foto_sampul')) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $request->file('foto_sampul')->getClientOriginalExtension();
            $fileName = $randomName.'.'.$fileExtension;

            $request->file('foto_sampul')->move(public_path('images'), $fileName);

            if (File::exists(public_path('images/'.$movie->foto_sampul))) {
                File::delete(public_path('images/'.$movie->foto_sampul));
            }

            $movie->update([
                'judul' => $request->judul,
                'sinopsis' => $request->sinopsis,
                'category_id' => $request->category_id,
                'tahun' => $request->tahun,
                'pemain' => $request->pemain,
                'foto_sampul' => $fileName,
            ]);
        } else {

            $movie->update([
                'judul' => $request->judul,
                'sinopsis' => $request->sinopsis,
                'category_id' => $request->category_id,
                'tahun' => $request->tahun,
                'pemain' => $request->pemain,
            ]);
        }

        return redirect('/movies/data')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $movie = Movie::findOrFail($id);

        // Delete the movie's photo if it exists
        if (File::exists(public_path('images/'.$movie->foto_sampul))) {
            File::delete(public_path('images/'.$movie->foto_sampul));
        }

        // Delete the movie record from the database
        $movie->delete();

        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }
}
