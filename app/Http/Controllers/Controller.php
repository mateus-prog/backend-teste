<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *
 *     @OA\Info(
 *         title="Teste API",
 *         version="1.0.0",
 *         description="Teste LIVRO"
 *     )
 * )
 *
 * @OA\Tag(
 *      name="Authors",
 *      description=""
 * )
 * @OA\Tag(
 *      name="Subjects",
 *      description=""
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
