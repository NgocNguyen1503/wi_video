<?php

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Comments\CommentRepositoryInterface;

class CommentEloquentRepository extends EloquentRepository implements CommentRepositoryInterface
{
    /**
     * Implement abstract method and base model
     *
     * @return mixed | model
     */
    public function getModel()
    {
        return Comment::class;
    }

    // Deploy special methods.
    public function getVideoComments($videoId)
    {
        return $this->_model->join('users', 'comments.user_id', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.avatar',
                'comments.id as comment_id',
                'comments.comment',
                'comments.created_at'
            )
            ->where('video_id', $videoId)
            ->orderBy('id', 'ASC')->get();
    }
}