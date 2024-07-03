<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\StorePostRequest;
use App\http\Resources\V1\PostResource;
use App\Http\Requests\UpdatePostRequest;
use App\http\Resources\V1\PostCollection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @group Posts
     * 
     */

    /**
     * @OA\Get(
     * path="/api/v1/posts",
     * summary="Get a list of Posts",
     * tags={"Posts"},
     * security={{"bearerAuth": {}},},
     * @OA\Response(response=200,description="Success"),
     * @OA\Response(response=401,description="Unauthenticated"),
     * @OA\Response(response=403,description="Forbidden"),
     * @OA\Response(response=404,description="Not Found"),
     * @OA\Response(response=500,description="Server Error"),
     * )
     */
    public function index()
    {

        $posts = QueryBuilder::for(Post::orderBy('created_at', 'desc'))
            ->allowedSorts(['created_at'])
            ->allowedFilters('user_id')
            ->with('user')
            ->paginate(100);
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     * @group Posts
     */

    /**
     * @OA\Post(
     * path="/api/v1/posts",
     * tags={"Posts"},
     * summary="create a new post",
     * @OA\Parameter(name="post", in="query", description="post",required=true,* @OA\Schema(type="string")),
     * @OA\Response(response=201,description="Post Created successfully "),
     * @OA\Response(response=200,description="Success"),
     * @OA\Response(response="422", description="Validation errors"),
     * @OA\Response(response=401,description="Unauthenticated"),
     * )
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['image'] = $request->file('image')->store('uploads', 'public');
        $post = Post::create($validated);

        if ($post) {
            return response()->json([
                'data' => new PostResource($post),
                'message' => 'Post Succesfully Created',
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     * @group Posts
     */

    /**
     * @OA\Get(
     * path="/api/v1/posts/{id}",
     * summary="Store a Display a  Post",
     * tags={"Posts"},
     * security={
     *  {"bearerAuth": {}},
     *   },
     *      @OA\Parameter(
     *          name="id",
     *          description="Post id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     * @OA\Response(
     * response=200,
     *  description="List of Posts",
     * ),
     *   @OA\Response(  *      response=404,
     *      description="not found"
     *   ),
     * )
     */
    public function show($id)
    {
        $post = Post::with('user')->find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found',
            ], Response::HTTP_NOT_FOUND);
        }
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     * @group Posts
     */

    /**
     * @OA\Put(
     * path="/api/v1/posts/{id}",
     * summary="Update a  Post",
     * tags={"Posts"},
     * @OA\Response(
     * response=200,
     * description="List of Posts",
     * ),
     * )
     */
    public function update(UpdatePostRequest $request, $id)
    {

        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found',
            ], Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $post);
        $validated = $request->validated();
        $post->update($validated);
        return response()->json([
            'data' => new PostResource($post),
            'message' => 'Post Succesfully Created',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     * @group Posts
     */

    /**
     * @OA\Delete(
     * path="/api/v1/posts/{id}",
     * summary="Remove a Post",
     * tags={"Posts"},
     * @OA\Parameter( name="id",description="Post id",  required=true,in="path", @OA\Schema(type="integer" )),
     * @OA\Response(response=200,description="Post deleted Successfully",),
     * @OA\Response(response=204,description="No Content",),
     * @OA\Response(response=404,description="Post Not Found",),
     * )
     */
    public function destroy($id)
    {
        

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found',
            ], Response::HTTP_NOT_FOUND);
        }
        Gate::authorize('delete', $post);
        $post->delete();
        return response()->json([
            'message' => 'Post Succesfully Deleted',
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Search for a specified resource from storage.
     * @group Posts
     */

    /**
     * @OA\Get(
     * path="/api/v1/posts/{searchword}",
     * summary="Search for a Post",
     * tags={"Posts"},
     * @OA\Response(
     * response=200,
     * description="List of Posts",
     * ),
     * )
     */
    public function search($searchword)
    {
        return new PostCollection(Post::with('user')->where('post', 'like', '%' . $searchword . '%')->paginate(5));
    }
}
