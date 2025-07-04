<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Services\AuthorService;
use App\Traits\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Authors",
 *     description="API Endpoints para gerenciamento de autores"
 * )
 */
class AuthorController extends Controller
{
    use Pagination;

    protected $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @OA\Get(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Lista todos os autores",
     *     description="Retorna uma coleção paginada de autores",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de autores",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *
     *                     @OA\Property(property="cod_au", type="integer", description="Código do Autor", example=1),
     *                     @OA\Property(property="nome", type="string", description="Nome do Autor", example="Main Branch"),
     *                     @OA\Property(property="created_at", type="string", description="Data e hora de criação do registro", example="01/01/2025 12:00:00"),
     *                     @OA\Property(property="updated_at", type="string", description="Data e hora de atualização do registro", example="01/02/2025 15:30:00")
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
            $authors = $this->authorService->all();

            return response()->json(new AuthorCollection($authors), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/authors",
     *     tags={"Authors"},
     *     summary="Cria um novo autor",
     *     description="Cria um autor a partir dos dados fornecidos",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"nome"},
     *
     *             @OA\Property(property="nome", type="string", example="José da Silva")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Autor criado com sucesso",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_au", type="integer", example=123),
     *             @OA\Property(property="nome", type="string", example="José da Silva"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-02T12:34:56Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validação falhou"
     *     )
     * )
     */
    public function store(AuthorRequest $request): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['nome']);
            $author = $this->authorService->store($validated);

            return response()->json(new AuthorResource($author), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/authors/{cod_au}",
     *     tags={"Authors"},
     *     summary="Retorna um autor pelo Codido do Autor",
     *
     *     @OA\Parameter(
     *         name="cod_au",
     *         in="path",
     *         description="Código do autor",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do autor",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_au", type="integer", example=123),
     *             @OA\Property(property="nome", type="string", example="José da Silva"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-02T12:34:56Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Autor não encontrado"
     *     )
     * )
     */
    public function show(string $cod_au): JsonResponse
    {
        try {
            $author = $this->authorService->findById($cod_au);

            return response()->json(new AuthorResource($author), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/authors/{cod_au}",
     *     tags={"Authors"},
     *     summary="Atualiza um autor existente",
     *
     *     @OA\Parameter(
     *         name="cod_au",
     *         in="path",
     *         description="Código do autor a ser atualizado",
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
     *             required={"nome"},
     *
     *             @OA\Property(property="nome", type="string", example="José da Silva")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Autor atualizado com sucesso, sem conteúdo retornado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Falha na validação"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor não encontrado"
     *     )
     * )
     */
    public function update(AuthorRequest $request, string $cod_au): JsonResponse|Response
    {
        try {
            $validated = $request->safe()->only(['nome']);
            $this->authorService->update($cod_au, $validated);

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
     *     path="/api/authors/{cod_au}",
     *     tags={"Authors"},
     *     summary="Deleta um autor pelo código do autor",
     *
     *     @OA\Parameter(
     *         name="cod_au",
     *         in="path",
     *         description="Código do autor a ser deletado",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Autor deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Autor não encontrado"
     *     )
     * )
     */
    public function destroy(string $cod_au): JsonResponse|Response
    {
        try {
            $this->authorService->destroy($cod_au);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }
}
