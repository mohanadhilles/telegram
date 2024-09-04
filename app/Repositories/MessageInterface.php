<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface MessageInterface
{
    /**
     * Create a new message.
     *
     * @param array $data
     * @return Message
     * @throws \Exception
     */
    public function createMessage(array $data);

    /**
     * Retrieve a message by its ID.
     *
     * @param int $id
     * @param int $userId
     * @return Message
     * @throws \Exception
     */
    public function getMessageById($id, $userId);

    /**
     * Get all messages for a specific user, optionally filtered by read/unread status.
     *
     * @param int $userId
     * @param bool|null $isRead
     */
    public function getMessagesForUser(int $userId, ?bool $isRead = null);

    /**
     * Get unread messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUnreadMessagesForUser(int $userId): Collection;

    /**
     * Get forwarded messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getForwardedMessagesForUser(int $userId): Collection;

    /**
     * Mark a specific message as read.
     *
     * @param int $messageId
     * @return bool
     */
    public function markMessageAsRead(int $messageId): bool;

    /**
     * Delete a message by its ID.
     *
     * @param int $messageId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMessage(int $messageId): ?bool;
}
