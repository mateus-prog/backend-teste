<?php

namespace App\Services;

use App\Repositories\Elouquent\SubjectRepository;
use App\Traits\Pagination;
use Illuminate\Database\Eloquent\Model;

class SubjectService
{
    use Pagination;

    protected $subjectRepository;

    public function __construct()
    {
        $this->subjectRepository = new SubjectRepository;
    }

    /**
     * Selecione todos os usuarios
     *
     * @return array
     */
    public function all()
    {
        return $this->subjectRepository->all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(array $request): Model
    {
        return $this->subjectRepository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findById(string $id): object
    {
        return $this->subjectRepository->findById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(string $id, array $request): Model
    {
        return $this->subjectRepository->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): bool
    {
        return $this->subjectRepository->delete($id);
    }
}
