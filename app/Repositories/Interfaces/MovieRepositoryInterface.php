<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface
{
    public function getPaginatedWithSearch($search, $perPage = 6);

    public function getPaginatedLatest($perPage = 10);

    public function findById($id);

    public function findOrFail($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
