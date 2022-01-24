<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UsersPaginationRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use JetBrains\PhpStorm\Pure;
use Nette\NotImplementedException;

class PostsController extends Controller
{
    public function index(): string
    {
        return 'index';
    }

    public function users_index(User $user, UsersPaginationRequest $request)
    {
        $postsQuery = $user->posts();
        if ($latestPost = $user->posts()->find($request->latest_post_id)) {
            $postsQuery->where('published_at', '>', $latestPost->published_at);
        }
        if ($oldestPost = $user->posts()->find($request->oldest_post_id)) {
            $postsQuery->where('published_at', '<', $oldestPost->published_at);
        }

        $postsQuery->limit(10);

        return response()->json([
            'posts' => PostResource::collection($postsQuery->get())
        ]);
    }

    public function my_index(UsersPaginationRequest $request)
    {
        $user = $request->attributes->get('auth.user');

        return $this->users_index($user, $request);
    }

    public function create(CreatePostRequest $request)
    {
        $user = User::castFromMixed($request->attributes->get('auth.user'));

        $newPost = new Post();
        $newPost->fill($request->validated());
        if ($request->is_publish) {
            $newPost->published_at = now();
        }
        $user->posts()->save($newPost);

        return response()->json([
            'post' => [
                'id' => $newPost->id
            ]
        ]);
    }

    #[Pure]
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function delete(Post $post)
    {
        throw new NotImplementedException;
    }
}
