<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //creat topic first
        $this->call(TopicSeeder::class);
        $topics = Topic::all();

        $users = User::factory(10)
      //  ->has(Post::factory(2))
        ->create();

        $posts= Post::factory(20)
        ->has(Comment::factory(15)->recycle([$users, $topics]));
        //$comments = Comment::factory(20)->recycle($users)->recycle($posts)->create();

        User::factory()
       // ->has(Post::factory(2))
       ->has(Post::factory(5)->recycle($topics))
        ->has(Comment::factory(20)->recycle($posts))
        ->create([
            'name' => 'Tahani',
            'email' => 'tahani@yahoo.com',
        ]);
    }
}
