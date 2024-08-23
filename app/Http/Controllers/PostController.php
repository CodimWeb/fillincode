<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/posts",
     *     summary="Post list",
     *     description="Post list",
     *     operationId="postList",
     *     tags={"Posts"},
     *     security={
     *         {"bearerAuth": {}}
     *     },

     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'posts' => Post::where('user_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create new post",
     *     operationId="createPost",
     *     tags={"Posts"},
     *     requestBody={"$ref": "#/components/requestBodies/PostRequest"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'post' => $post
        ]);
    }

    /**
     * @OA\Get (
     *     path="/api/posts/{post_id}",
     *     summary="Show post",
     *     operationId="showPost",
     *     tags={"Posts"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         example="1",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'post' => Post::where('id', $id)->get()
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{post_id}",
     *     summary="update post",
     *     operationId="updatePost",
     *     tags={"Posts"},
     *     requestBody={"$ref": "#/components/requestBodies/PostRequest"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         example="1",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        UserHelper::checkAuthor($post->user_id);

        return response()->json([
            'success' => $post->update($request->all()),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{post_id}",
     *     summary="delete post",
     *     operationId="deletePost",
     *     tags={"Posts"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         example="1",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */
    public function destroy(Post $post)
    {
        UserHelper::checkAuthor($post->user_id);
        ;
        return response()->json([
            'success' => $post->delete()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/post/{post_id}/comments",
     *     summary="show post with comments",
     *     operationId="showPostWithComments",
     *     tags={"Posts"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         example="1",
     *         required=true,
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */
    public function getPostWithComments(string $id): JsonResponse
    {
        return response()->json([
            'post' => Post::where('id', $id)->with('comments')->get()
        ]);
    }
}
