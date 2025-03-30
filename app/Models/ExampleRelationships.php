<?php

namespace App\Models;

use Framework\Database\Model;

class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];

    /**
     * Get the posts authored by the user.
     *
     * @return \Framework\Database\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the user's profile
     *
     * @return \Framework\Database\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the roles assigned to the user
     *
     * @return \Framework\Database\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'content'];

    /**
     * Get the author of the post
     *
     * @return \Framework\Database\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the comments for this post
     *
     * @return \Framework\Database\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the categories this post belongs to
     *
     * @return \Framework\Database\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}

class Profile extends Model
{
    protected $fillable = ['user_id', 'bio', 'avatar'];

    /**
     * Get the user that owns this profile
     *
     * @return \Framework\Database\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content'];

    /**
     * Get the post this comment belongs to
     *
     * @return \Framework\Database\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the author of this comment
     *
     * @return \Framework\Database\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the posts in this category
     *
     * @return \Framework\Database\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get the users that have this role
     *
     * @return \Framework\Database\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

// Example usage:
/*
// Retrieving relationships
$user = User::find(1);
$posts = $user->posts;  // Get all posts by this user

// Eager loading to avoid N+1 problem
$users = User::with('posts')->get();
foreach ($users as $user) {
    foreach ($user->posts as $post) {
        echo $post->title;
    }
}

// Using relationship methods for queries
$user = User::find(1);
$recentPosts = $user->posts()->where('created_at', '>=', '2023-01-01')->get();

// Creating related models
$user = User::find(1);
$post = $user->posts()->create([
    'title' => 'New Post Title',
    'content' => 'Content for the new post'
]);

// Many-to-many relationships
$user = User::find(1);
$user->roles()->attach(2);  // Add a role with ID 2 to the user
$user->roles()->detach(2);  // Remove a role with ID 2 from the user

// Belongs to relationship
$post = Post::find(1);
$author = $post->author;  // Get the author of this post
*/