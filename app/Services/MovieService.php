<?php

namespace App\Services;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieService
{
    protected $movieRepository;

    // Dependency Injection Repository ke dalam Service
    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMoviesForHomepage($search)
    {
        return $this->movieRepository->getPaginatedWithSearch($search, 6);
    }

    public function getMoviesForAdmin()
    {
        return $this->movieRepository->getPaginatedLatest(10);
    }

    public function getMovieById($id)
    {
        return $this->movieRepository->findById($id);
    }

    public function storeMovie(array $data, $file = null)
    {
        if ($file) {
            $data['foto_sampul'] = $this->uploadImage($file);
        }

        return $this->movieRepository->create($data);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $movie = $this->movieRepository->findOrFail($id);

        if ($file) {
            $this->deleteImage($movie->foto_sampul);
            $data['foto_sampul'] = $this->uploadImage($file);
        }

        return $this->movieRepository->update($id, $data);
    }

    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->findOrFail($id);

        $this->deleteImage($movie->foto_sampul);

        return $this->movieRepository->delete($id);
    }

    /**
     * ==========================================
     * CLEAN CODE: Ekstraksi Fungsi
     * ==========================================
     */
    private function uploadImage($file)
    {
        $randomName = Str::uuid()->toString();
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = $randomName.'.'.$fileExtension;

        $file->move(public_path('images'), $fileName);

        return $fileName;
    }

    private function deleteImage($fileName)
    {
        if ($fileName && File::exists(public_path('images/'.$fileName))) {
            File::delete(public_path('images/'.$fileName));
        }
    }
}
