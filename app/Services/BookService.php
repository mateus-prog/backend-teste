<?php

namespace App\Services;

use App\Repositories\Elouquent\BookRepository;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model;

class BookService
{
    use Pagination;

    protected $bookRepository;

    public function __construct()
    {
        $this->bookRepository = new BookRepository;
    }

    /**
     * Selecione todos os usuarios
     *
     * @return array
     */
    public function all()
    {
        return $this->bookRepository->all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(array $request): Model
    {
        return $this->bookRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findById(string $id): object
    {
        return $this->bookRepository->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(string $id, array $request): Model
    {
        return $this->bookRepository->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): bool
    {
        return $this->bookRepository->delete($id);
    }

    public function syncRelations($book, ?array $cod_au = [], ?array $cod_as = []): void
    {
        // Relaciona autores
        if (! empty($cod_au)) {
            $book->authors()->sync($cod_au);
        }

        // Relaciona assuntos
        if (! empty($cod_as)) {
            $book->subjects()->sync($cod_as);
        }
    }
}
