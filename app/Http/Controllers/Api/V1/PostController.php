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
     */
    public function index()
    {

        $posts = QueryBuilder::for(Post::class)
            ->allowedSorts(['created_at'])
            ->allowedFilters('user_id')
            ->allowedIncludes('user')
            ->paginate(5);
        return new PostCollection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function show(Post $post)
    {
        $post = Post::with('user')->find($post->id);
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $validated = $request->validated();
        $post->update($validated);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $post = Post::find($id);
        Gate::authorize('delete', $post);
        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found',
            ]);
        }
        $post->delete();
        return response()->json([
            'message' => 'Post Succesfully Deleted',
        ]);
    }

    public function search($searchword)
    {
        return new PostCollection(Post::with('user')->where('post', 'like', '%' . $searchword . '%')->paginate(5));
    }
}
