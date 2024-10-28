<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::with('user')->orderBy('created_at', 'desc')->get();
        return view('home', compact('posts'));
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
    public function store(Request $request)
    {
        //
        $request->validate([
            'content' => 'required',
            'post_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        // Handle the file upload
        if ($request->hasFile('post_picture')) {
            $postPicture = $request->file('post_picture');
            $postPicturePath = $postPicture->store('post_picture', 'public'); // Store in 'storage/app/public/post_picture'
        } else {
            $postPicturePath = null;
        }

        Post::create([
            'content' => $request->content,
            'post_picture'=> $postPicturePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post  = Post::findorfail($id);
        if ($post->user_id !== auth()->id()) {
            abort(403, 'anauthorize access');
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $post  = Post::findorfail($id);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'anauthorize action');
        }

        $request->validate([
            'content' => 'required',
            'post_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle file upload for profile picture
        if ($request->hasFile('post_picture')) {
            if ($post->post_picture) {
                // Delete the old profile picture if it exists
                Storage::disk('public')->delete($post->post_picture);
            }

            // Store the new profile picture
            $postPicturePath = $request->file('post_picture')->store('post_pictures', 'public');
            $validatedData['post_picture'] = $postPicturePath;
        } else {
            // Remove post_picture from validated data if no new file is uploaded
            unset($validatedData['post_picture']);
        }

        $post->update([
            'content' => $request->content,
            'post_picture'=> $postPicturePath,
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Post::findOrFail($id);

        // Ensure only the owner can delete the post
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $post->delete();
        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }
}
