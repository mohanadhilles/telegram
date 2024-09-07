<?php

namespace App\Repositories;

use App\Models\ForwardedMessage;
use App\Models\Message;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class ForwardedMessageRepository implements ForwardedMessageInterface
{

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        try {
            Message::query()->where('id', $data['original_message_id'])
                ->update([
                    'is_forwarded' => 1,
                ]);

            return ForwardedMessage::create($data);
        } catch (\Exception $e) {
            app('log')->error('Error creating ForwardedMessage: ' . $e->getMessage());
            throw new \Exception(__('messages.forwarded_message_creation_failed'));
        }
    }
    /**
     * Get all forwarded messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getForwardedMessagesForUser(int $userId): Collection
    {
        try {
            return ForwardedMessage::where('forwarded_to_user_id', $userId)
                ->with('originalMessage')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (QueryException $e) {
            app('log')->error('Database query error: [getForwardedMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.query_error'));
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [getForwardedMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Delete a forwarded message by its ID.
     *
     * @param int $forwardedMessageId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteForwardedMessage(int $forwardedMessageId): ?bool
    {
        try {
            $forwardedMessage = ForwardedMessage::find($forwardedMessageId);

            if ($forwardedMessage) {
                return $forwardedMessage->delete();
            }

            return false;
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [deleteForwardedMessage] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }
}
