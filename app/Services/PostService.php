<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * Layer to call and perform datastore operations
 */
class PostService
{

    /**
     * Variable to hold injected dependency
     *
     * @var $postRepository
     */
    protected $postRepository;

    /**
     * Initializing the instances and variables
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Get all posts
     */
    public function getAll(){
        return $this->postRepository->getAllPosts();
    }

    public function getById($id){
        return $this->postRepository->getById($id);
    }

    /**
     * Validate post data.
     * Store to DB if there is no error.
     * @param $data
     * @return String
     */
    public function savePostData($data): String
    {
        $validator = Validator::make($data, [
           "title" => "required",
           "description" => "required"
        ]);

        if($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->postRepository->save($data);
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        $validator = Validator::make($data, [
            'title' => 'bail|min:2',
            'description' => 'bail|max:255'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $post = $this->postRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

    }

    /**
     * Delete by id service
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->postRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }
}
