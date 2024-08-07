<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;



class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    // public function index(Topic $topic = null)
    // {
    //     $posts = Post::with(['user', 'topic'])
    //         ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
    //         ->latest()
    //         ->latest('id')
    //         ->paginate();

    //     return inertia('Posts/Index', [
    //         'posts' => PostResource::collection($posts),
    //         'topics' => fn () => TopicResource::collection(Topic::all()),
    //         'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
    //     ]);
    // }

    //search 

    public function index(Request $request, Topic $topic = null)
    {
        $posts = Post::with(['user', 'topic'])
            ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
            // where(...) + orwhere(...) === whereAny([...],...)

            ->when(
                $request->query('query'),
                fn (Builder $query) => $query->whereAny(['title', 'body'], 'like', '%' . $request->query('query') . '%')
            )
            ->latest()
            ->latest('id')
            ->paginate()
            ->withQueryString(); // when using search pagination must stil work with all serach results

        return inertia('Posts/Index', [
            'posts' => PostResource::collection($posts),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
            'query' => $request->query('query'),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Post::class);
        return inertia('Posts/Create', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    // public function store(StorePostRequest $request)

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'topic_id' => ['required', 'exists:topics,id'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        //  return to_route('posts.show', $post);
        //use slug 
        return redirect($post->showRoute());
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        //use request to update slug url 
        // if (! Str::contains($post->showRoute(), $request->path())) {
        if (!Str::endsWith($post->showRoute(), $request->path())) {

            return redirect($post->showRoute($request->query()), status: 301); //give page of any other pram
        }

        $post->load('user', 'topic');

        return inertia('Posts/Show', [

            // 'post' => PostResource::make($post), // fn () => It is faster because it is only executed when we need to pass to the front end.
            // 'post' => fn () => PostResource::make($post),
            'post' => fn () => PostResource::make($post)->withLikePermission(),
            //       'comments' => fn () => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10)),
            //becouse this is not a resouce that can use withLikePermission method directly , it is a collection , sp we tansferr coltion to resoce then use method  

            'post' => fn () => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {
                $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10));
                $commentResource->collection->transform(fn ($resource) => $resource->withLikePermission());

                return $commentResource;
            },
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
