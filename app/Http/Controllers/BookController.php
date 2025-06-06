<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use App\Traits\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints para gerenciamento de livros"
 * )
 */
class BookController extends Controller
{
    use Pagination;

    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Lista todos os livros",
     *     description="Retorna uma coleção paginada de livros",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de livros",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *
     *                     @OA\Property(property="cod_li", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Dom Casmurro"),
     *                     @OA\Property(property="editora", type="string", example="Editora Globo"),
     *                     @OA\Property(property="edicao", type="string", example=3),
     *                     @OA\Property(property="ano_publicacao", type="integer", example=1995),
     *                     @OA\Property(property="preco", type="number", format="float", example=59.90),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-02T12:34:56Z")
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Cria um novo livro",
     *     description="Cria um livro com dados e relacionamentos (autores e assuntos)",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"titulo", "editora", "edicao", "ano_publicacao", "preco", "cod_au", "cod_as"},
     *
     *             @OA\Property(property="titulo", type="string", example="O Cortiço"),
     *             @OA\Property(property="editora", type="string", example="Companhia das Letras"),
     *             @OA\Property(property="edicao", type="string", example=1),
     *             @OA\Property(property="ano_publicacao", type="integer", example=1995),
     *             @OA\Property(property="preco", type="number", format="float", example=45.50),
     *             @OA\Property(property="cod_au", type="array", @OA\Items(type="integer"), example={1, 2}),
     *             @OA\Property(property="cod_as", type="array", @OA\Items(type="integer"), example={3, 4})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Livro criado com sucesso",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_l", type="integer", example=10),
     *             @OA\Property(property="titulo", type="string", example="O Cortiço"),
     *             @OA\Property(property="editora", type="string", example="Companhia das Letras"),
     *             @OA\Property(property="edicao", type="string", example=1),
     *             @OA\Property(property="ano_publicacao", type="integer", example=1995),
     *             @OA\Property(property="preco", type="number", format="float", example=45.50),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-05T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-05T12:00:00Z"),
     *             @OA\Property(
     *                 property="authors",
     *                 type="array",
     *
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *
     *             @OA\Property(
     *                 property="subjects",
     *                 type="array",
     *
     *                 @OA\Items(type="integer", example=3)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Falha de validação"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/books/{cod_l}",
     *     tags={"Books"},
     *     summary="Retorna um livro pelo código do livro",
     *
     *     @OA\Parameter(
     *         name="cod_l",
     *         in="path",
     *         description="Código do livro",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Livro encontrado",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_l", type="integer", example=10),
     *             @OA\Property(property="titulo", type="string", example="O Cortiço"),
     *             @OA\Property(property="editora", type="string", example="Companhia das Letras"),
     *             @OA\Property(property="edicao", type="string", example=1),
     *             @OA\Property(property="ano_publicacao", type="integer", example=1995),
     *             @OA\Property(property="preco", type="number", format="float", example=45.50),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-05T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-05T12:00:00Z"),
     *             @OA\Property(
     *                 property="authors",
     *                 type="array",
     *
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *
     *             @OA\Property(
     *                 property="subjects",
     *                 type="array",
     *
     *                 @OA\Items(type="integer", example=3)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Livro não encontrado"
     *     )
     * )
     */
    public function show(string $cod_l): JsonResponse
    {
        try {
            $book = $this->bookService->findById($cod_l);

            return response()->json(new BookResource($book), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/books/{cod_l}",
     *     tags={"Books"},
     *     summary="Atualiza um livro existente",
     *
     *     @OA\Parameter(
     *         name="cod_l",
     *         in="path",
     *         description="Código do livro",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"titulo", "editora", "edicao", "ano_publicacao", "preco", "cod_au", "cod_as"},
     *
     *             @OA\Property(property="titulo", type="string", example="O Cortiço"),
     *             @OA\Property(property="editora", type="string", example="Companhia das Letras"),
     *             @OA\Property(property="edicao", type="string", example=2),
     *             @OA\Property(property="ano_publicacao", type="integer", example=1995),
     *             @OA\Property(property="preco", type="number", format="float", example=50.00),
     *             @OA\Property(property="cod_au", type="array", @OA\Items(type="integer"), example={1}),
     *             @OA\Property(property="cod_as", type="array", @OA\Items(type="integer"), example={3})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Livro atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Livro não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Falha de validação"
     *     )
     * )
     */
    public function update(BookRequest $request, string $cod_l): JsonResponse|Response
    {
        try {
            $validated = $request->safe()->only(['titulo', 'editora', 'edicao', 'ano_publicacao', 'preco']);
            $this->bookService->update($cod_l, $validated);

            $book = $this->bookService->findById($cod_l);
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
     * @OA\Delete(
     *     path="/api/books/{cod_l}",
     *     tags={"Books"},
     *     summary="Remove um livro pelo código do livro",
     *
     *     @OA\Parameter(
     *         name="cod_l",
     *         in="path",
     *         description="Código do livro",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Livro removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Livro não encontrado"
     *     )
     * )
     */
    public function destroy(string $cod_l): JsonResponse|Response
    {
        try {
            $this->bookService->destroy($cod_l);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }
}
