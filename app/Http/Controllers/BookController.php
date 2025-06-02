<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use App\Traits\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use Pagination;

    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $books = $this->bookService->all();

            return response()->json(new BookCollection($books), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['titulo', 'editora', 'edicao', 'ano_publicacao', 'preco']);
            $book = $this->bookService->store($validated);

            $this->bookService->syncRelations($book, $request->cod_au, $request->cod_as);

            return response()->json(new BookResource($book), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $book = $this->bookService->findById($id);

            return response()->json(new BookResource($book), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id): JsonResponse|Response
    {
        try {
            $validated = $request->safe()->only(['titulo', 'editora', 'edicao', 'ano_publicacao', 'preco']);
            $this->bookService->update($id, $validated);

            $book = $this->bookService->findById($id);
            $this->bookService->syncRelations($book, $request->cod_au, $request->cod_as);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse|Response
    {
        try {
            $this->bookService->destroy($id);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }
}
