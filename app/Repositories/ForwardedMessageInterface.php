<?php

namespace App\Repositories;

use App\Models\ForwardedMessage;
use Illuminate\Support\Collection;

interface ForwardedMessageInterface
{
    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data);

    /**
     * Get all forwarded messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getForwardedMessagesForUser(int $userId): Collection;

    /**
     * Create a new forwarded message.
     *
     * @param array $data
     * @return ForwardedMessage
     */
    public function createForwardedMessage(array $data): ForwardedMessage;

    /**
     * Delete a forwarded message by its ID.
     *
     * @param int $forwardedMessageId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteForwardedMessage(int $forwardedMessageId): ?bool;
}
