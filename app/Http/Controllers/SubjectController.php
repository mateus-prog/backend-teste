<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectCollection;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use App\Traits\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Subjects",
 *     description="API Endpoints para gerenciamento de Assuntos"
 * )
 */
class SubjectController extends Controller
{
    use Pagination;

    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     * @OA\Get(
     *     path="/api/subjects",
     *     tags={"Subjects"},
     *     summary="Lista todos os assuntos",
     *     description="Retorna uma coleção paginada de assuntos",
     *
     *     @OA\Parameter(
     *       name="descricao",
     *       in="query",
     *       required=true,
     *
     *       @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de assuntos",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *
     *                     @OA\Property(property="cod_as", type="integer", description="The ID of the branch", example=1),
     *                     @OA\Property(property="descricao", type="string", description="The name of the branch", example="Main Branch"),
     *                     @OA\Property(property="created_at", type="string", description="The creation timestamp", example="01/01/2025 12:00:00"),
     *                     @OA\Property(property="updated_at", type="string", description="The last updated timestamp", example="01/02/2025 15:30:00")
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
            $subjects = $this->subjectService->all();

            return response()->json(new SubjectCollection($subjects), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/subjcts",
     *     tags={"Subjects"},
     *     summary="Cria um novo assunto",
     *     description="Cria um assunto a partir dos dados fornecidos",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"descricao"},
     *
     *             @OA\Property(property="descricao", type="string", example="José da Silva")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Assunto criado com sucesso",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_as", type="integer", example=123),
     *             @OA\Property(property="descricao", type="string", example="José da Silva"),
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
    public function store(SubjectRequest $request): JsonResponse
    {
        try {
            $validated = $request->safe()->only(['descricao']);
            $subject = $this->subjectService->store($validated);

            return response()->json(new SubjectResource($subject), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/subjects/{id}",
     *     tags={"Subjects"},
     *     summary="Retorna um assunto pelo ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do assunto",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do assunto",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="cod_as", type="integer", example=123),
     *             @OA\Property(property="descricao", type="string", example="José da Silva"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-02T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-02T12:34:56Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Assunto não encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $subject = $this->subjectService->findById($id);

            return response()->json(new SubjectResource($subject), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/subjects/{id}",
     *     tags={"Subjects"},
     *     summary="Atualiza um assunto existente",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do assunto a ser atualizado",
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
     *             required={"descricao"},
     *
     *             @OA\Property(property="descricao", type="string", example="José da Silva")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Assunto atualizado com sucesso, sem conteúdo retornado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Falha na validação"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Assunto não encontrado"
     *     )
     * )
     */
    public function update(SubjectRequest $request, string $id): JsonResponse|Response
    {
        try {
            $validated = $request->safe()->only(['descricao']);
            $this->subjectService->update($id, $validated);

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
     *     path="/api/subjects/{id}",
     *     tags={"Subjects"},
     *     summary="Deleta um assunto pelo ID",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do assunto a ser deletado",
     *         required=true,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Assunto deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Assunto não encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse|Response
    {
        try {
            $this->subjectService->destroy($id);

            return response()->noContent();
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getCode(),
            ], $e->getCode());
        }
    }
}
