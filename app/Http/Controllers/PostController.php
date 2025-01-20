<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::with('user')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        // Generate unique URI from title
        $validated['uri'] = Post::generateUniqueUri($validated['title']);
        
        $post = $request->user()->posts()->create($validated);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return new PostResource($post->load('user'));
    }

    public function showByUri($uri)
    {
        $post = Post::where('uri', $uri)->firstOrFail();
        return new PostResource($post->load('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $post = Post::findOrFail($id);

        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        
        // $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Only generate new URI if title has changed
        if ($post->title !== $validated['title']) {
            $validated['uri'] = Post::generateUniqueUri($validated['title']);
        }

        $post->update($validated);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $post = Post::findOrFail($id);


        if ($request->user()->cannot('update', $post)) {
            abort(403);
        }
        
        // $this->authorize('delete', $post);

        $post->delete();


        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
