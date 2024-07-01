<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\V1\UserResource;
use App\http\Resources\V1\UserCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @group Users
     */
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedSorts(['name','email','created_at'])
            ->withCount('posts')
            ->paginate(5);
        return new UserCollection($users);
        // return new UserCollection(User::withcount('posts')->paginate(5));
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
     * @group Users
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @group Users
     */
    public function show($id)
    {
        $user = User::withcount('posts')->with('posts')->find($id);
        if(!$user){
            return response()->json([
                'message'=> 'User Not ound',
            ],Response::HTTP_NOT_FOUND);
        }
        return new UserResource($user);
    }

}
