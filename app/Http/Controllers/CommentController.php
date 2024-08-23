<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/comments",
     *     summary="Comments list",
     *     description="Comments list",
     *     operationId="commentsList",
     *     tags={"Comments"},
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
            'comments' => Comment::where('user_id', auth()->user()->id)->get()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create new comment",
     *     operationId="createComment",
     *     tags={"Comments"},
     *     requestBody={"$ref": "#/components/requestBodies/CommentsRequest"},
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
    public function store(CommentRequest $request): JsonResponse
    {
        $comment = Comment::create([
            'body' => $request->input('body'),
            'post_id' => $request->input('post_id'),
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'comment' => $comment
        ]);
    }

    /**
     * @OA\Get (
     *     path="/api/comments/{comment_id}",
     *     summary="Show comment",
     *     operationId="showComments",
     *     tags={"Comments"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="comment_id",
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
            'comment' => Comment::where('id', $id)->get()
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{comment_id}",
     *     summary="update comment",
     *     operationId="updateComment",
     *     tags={"Comments"},
     *     requestBody={"$ref": "#/components/requestBodies/CommentRequest"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="comment_id",
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
    public function update(CommentRequest $request, Comment $comment): JsonResponse
    {
        UserHelper::checkAuthor($comment->user_id);
        $comment->update($request->all());
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{comment_id}",
     *     summary="delete comment",
     *     operationId="deleteComment",
     *     tags={"Comments"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="comment_id",
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
    public function destroy(Comment $comment): JsonResponse
    {
        UserHelper::checkAuthor($comment->user_id);
        $comment->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
