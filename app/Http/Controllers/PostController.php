<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->paginate(2);
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:2|max:100',
            'url' => 'required|alpha_dash|min:2|max:100',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $name = md5($request->image . time()) . '.' . $request->image->extension();
        $request->file('image')->move(public_path('uploads'), $name);

        $post = new Post;
        $post->title = $request->title;
        $post->url = $request->url;
        $post->image = 'uploads/' . $name;
        $post->save();

        return redirect()->route('posts.index');
    }

/**
 * Display the specified resource.
 */
public function show(string $id)
{
    $post = Post::findOrFail($id);
    return view('posts.show', compact('post'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|min:2|max:100',
            'url' => 'required|alpha_dash|min:2|max:100',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            File::delete(public_path($post->image));
            $name = md5($request->image . time()) . '.' . $request->image->extension();
            $request->file('image')->move(public_path('uploads'), $name);
            $post->image = 'uploads/' . $name;
        }

        $post->title = $request->title;
        $post->url = $request->url;
        $post->save();

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        File::delete(public_path($post->image));
        $post->delete();
        return redirect()->route('posts.index');
    }
}
