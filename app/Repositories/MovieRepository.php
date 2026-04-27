<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getPaginatedWithSearch($search, $perPage = 6)
    {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%'.$search.'%')
                ->orWhere('sinopsis', 'like', '%'.$search.'%');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getPaginatedLatest($perPage = 10)
    {
        return Movie::latest()->paginate($perPage);
    }

    public function findById($id)
    {
        return Movie::find($id);
    }

    public function findOrFail($id)
    {
        return Movie::findOrFail($id);
    }

    public function create(array $data)
    {
        return Movie::create($data);
    }

    public function update($id, array $data)
    {
        $movie = $this->findOrFail($id);
        $movie->update($data);

        return $movie;
    }

    public function delete($id)
    {
        $movie = $this->findOrFail($id);

        return $movie->delete();
    }
}
