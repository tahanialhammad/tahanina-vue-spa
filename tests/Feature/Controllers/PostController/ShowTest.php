<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;

use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

it('can show a post', function () {
    $post = Post::factory()->create();

   // get(route('posts.show', $post)) 
   //use slug
   get($post->showRoute())
    // ->assertInertia(fn (AssertableInertia $inertia) =>  $inertia
    //  ->component('Posts/Show', true)); //refacture this in TestingServiceProvider
        ->assertComponent('Posts/Show');
});

it('passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load('user');

   // get(route('posts.show', $post))
   //with slug
    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});

it('passes comments to the view', function () {

  //  $this->withoutExceptionHandling(); // to see the eeror about lazy load 
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    // get(route('posts.show', $post))
     //with slug
     get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse())); //latest coomments first
});

// test showroute rredirect
it('will redirect if the slug is incorrect', function () {
  $post = Post::factory()->create(['title' => 'Hello world']);

  get(route('posts.show', [$post, 'foo-bar', 'page' => 2]))
      ->assertRedirect($post->showRoute(['page' => 2]));
});