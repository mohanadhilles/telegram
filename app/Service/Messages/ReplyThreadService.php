<?php

namespace App\Service\Messages;

use App\Repositories\ReplyThreadInterface;

class ReplyThreadService
{
    protected $repository;

    public function __construct(ReplyThreadInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createReplyThread(array $data)
    {
        return $this->repository->create($data);
    }

    public function getReplyThreadById($id)
    {
        return $this->repository->find($id);
    }
}
