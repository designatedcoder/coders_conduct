<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $posts = Post::latest()->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title' => ['required', 'min:5', 'max:25'],
            'body' => ['required', 'min:5', 'max:100'],
        ]);
        auth()->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body
        ]);
        return response(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post) {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post) {
        $this->validate($request, [
            'title' => ['required', 'min:5', 'max:25'],
            'body' => ['required', 'min:5', 'max:100'],
        ]);
        if (auth()->user()->id != $post->user_id) {
            return response([
                'errors'=> [
                    'auth' => 'You do not have permission to edit this.'
                ],
            ], 401);
        }
        $post->update([
            'title' => $request->title,
            'body' => $request->body
        ]);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post) {
        if (auth()->user()->id != $post->user_id) {
            return response([
                'errors'=> [
                    'auth' => 'You do not have permission to edit this.'
                ],
            ], 401);
        }
        return $post->delete();
    }
}
