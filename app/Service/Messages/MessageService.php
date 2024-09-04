<?php

namespace App\Service\Messages;

use App\Repositories\MessageInterface;

class MessageService
{
    protected $messageRepo;

    public function __construct(MessageInterface $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    /**
     * Create a new message.
     *
     * @param array $data
     * @return \App\Models\Message
     * @throws \Exception
     */
    public function createMessage(array $data)
    {
        return $this->messageRepo->createMessage($data);
    }

    /**
     * Retrieve a message by its ID.
     *
     * @param int $id
     * @param int $userId
     * @return \App\Models\Message
     * @throws \Exception
     */
    public function getMessageById($id, $userId)
    {
        return $this->messageRepo->getMessageById($id, $userId);
    }

    /**
     * Retrieve messages for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getMessagesForUser($userId)
    {
        return $this->messageRepo->getMessagesForUser($userId);
    }

}
