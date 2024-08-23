<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
     *     request="CommentRequest", required=true,
     *     description="Create comment",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="body", type="string", example="Some body",
     *                 description="Comment body"
     *             ),
     *             @OA\Property(
     *                 property="post_id", type="number", example="1",
     *                 description="post id"
     *             ),
     *             required={"body", "post_id"}
     *         )
     *     )
     * )
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'post_id' => 'required|integer|exists:App\Models\Post,id',
        ];
    }
}
