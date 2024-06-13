<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\http\Resources\V1\PostResource;
use App\http\Resources\V1\PostCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new PostCollection(Post::with('user')->paginate(5));
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
        $validated ['user_id'] = 1;
        $post = Post::create($validated);
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $post->update($validated);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
       $post->delete();
        return response()->json([
            'message' =>'Post Succesfully Deleted',
            'status' => Response::HTTP_OK,
        ]);
       
    }

    public function search($searchword)
    {
         return new PostCollection(Post::with('user')->where('post', 'like', '%'.$searchword.'%')->paginate(5));
    }
}
