<?php

namespace App\Repositories\Comments;

interface CommentRepositoryInterface
{
    // Define Specialized methods.

    // Get video's comments by video's id
    public function getVideoComments($videoId);
}