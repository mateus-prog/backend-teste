<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:40'],
            'editora' => ['required', 'string', 'max:40'],
            'edicao' => ['required', 'integer', 'min:1'],
            'ano_publicacao' => ['required', 'date_format:Y'],
            'preco' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'cod_au' => ['required', 'array'],
            'cod_as' => ['required', 'array'],
            'cod_au.*' => ['exists:autores,cod_au'],
            'cod_as.*' => ['exists:assuntos,cod_as'],
        ];
    }
}
