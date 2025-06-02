<?php

namespace App\Services;

use App\Repositories\Elouquent\AuthorRepository;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model;

class AuthorService
{
    use Pagination;

    protected $authorRepository;

    public function __construct()
    {
        $this->authorRepository = new AuthorRepository;
    }

    /**
     * Selecione todos os usuarios
     *
     * @return array
     */
    public function all()
    {
        return $this->authorRepository->all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(array $request): Model
    {
        return $this->authorRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findById(string $id): object
    {
        return $this->authorRepository->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(string $id, array $request): Model
    {
        return $this->authorRepository->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): bool
    {
        return $this->authorRepository->delete($id);
    }
}
