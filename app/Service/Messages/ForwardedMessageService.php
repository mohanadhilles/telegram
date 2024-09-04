<?php

namespace App\Service\Messages;

use App\Repositories\ForwardedMessageInterface;

class ForwardedMessageService
{
    protected $repository;

    public function __construct(ForwardedMessageInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createForwardedMessage(array $data)
    {
        return $this->repository->create($data);
    }

    public function getForwardedMessageById($id)
    {
        return $this->repository->find($id);
    }
}
