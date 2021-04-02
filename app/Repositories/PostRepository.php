<?php

namespace App\Repositories;

use App\Models\Post;

/**
 * Layer to handle datastore operations. Can be a local operation or external datastore
 */
class PostRepository
{

    /**
     * Variable to hold injected dependency
     *
     * @var [type]
     */
    protected $post;

    /**
     * Initializing the instances and variables
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


    /**
     * Get all posts.
     */
    public function getAllPosts(){
        return $this->post->get();
    }

    /**
     * Get Post by id
     * @param $id
     * @return Post
     */
    public function getById($id): Post
    {
        return $this->post->where('id', $id)->get();
    }

    /**
     * @param $data
     * @return Post
     */
    public function save($data): Post
    {
        $post = new $this->post;

        $post -> title = $data['title'];
        $post -> description = $data['description'];

        $post->save();

        return $post->fresh();
    }

    /**
     * Update Post
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($data, $id)
    {

        $post = $this->post->find($id);

        $post->title = $data['title'];
        $post->description = $data['description'];

        $post->update();

        return $post;
    }

    /**
     * Delete Post
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {

        $post = $this->post->find($id);
        $post->delete();

        return $post;
    }


}
