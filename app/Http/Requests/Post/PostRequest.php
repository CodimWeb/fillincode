<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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

    /**
     * @OA\RequestBody(
     *     request="PostRequest", required=true,
     *     description="Create post",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="title", type="string", example="Some title",
     *                 description="Post title"
     *             ),
     *             @OA\Property(
     *                 property="body", type="string", example="Some body",
     *                 description="Post body"
     *             ),
     *             required={"title", "body"}
     *         )
     *     )
     * )
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ];
    }
}
