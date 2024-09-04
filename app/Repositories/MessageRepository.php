<?php

namespace App\Repositories;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class MessageRepository implements MessageInterface
{
    /**
     * Create a new message.
     *
     * @param array $data
     * @return Message
     * @throws \Exception
     */
    public function createMessage(array $data)
    {
        try {
            $message = Message::create($data);
            event(new MessageSent($message));
            return $message;
        } catch (QueryException $e) {
            app('log')->error('Database query error: [createMessage]' . $e->getMessage());
            throw new \Exception(__('messages.message_creation_error'));
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [createMessage]' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Retrieve a message by its ID.
     *
     * @param int $id
     * @param int $userId
     * @return Message
     * @throws \Exception
     */
    public function getMessageById($id, $userId, ?bool $isRead = null)
    {
        try {
            return Message::with(['media','receiver:id,name,photo,is_online']) // Eager load receiver's details
                ->when($isRead !== null, function ($query) use ($isRead) {
                    $query->where('is_read', $isRead);
                })
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                })->where('id', $id)
                ->first();

        } catch (ModelNotFoundException $e) {
            app('log')->error('Message not found: ' . $e->getMessage());
            throw new \Exception(__('messages.message_not_found'));
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Get all messages for a specific user, optionally filtered by read/unread status.
     *
     * @param int $userId
     * @param bool|null $isRead
     */
    public function getMessagesForUser(int $userId, ?bool $isRead = null)
    {
        try {
            $query = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
                ->with(['receiver:id,name,photo,is_online']) // Eager load receiver's details
                ->when($isRead !== null, function ($query) use ($isRead) {
                    $query->where('is_read', $isRead);
                })
                ->orderBy('updated_at', 'desc')->simplePaginate(config('settings.pagination'));

            return $query;
        } catch (QueryException $e) {
            app('log')->error('Database query error: [getMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.query_error'));
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [getMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Get unread messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUnreadMessagesForUser(int $userId): Collection
    {
        try {
            return Message::unread()
                ->where('receiver_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (QueryException $e) {
            app('log')->error('Database query error: [getUnreadMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.query_error'));
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [getUnreadMessagesForUser] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Get forwarded messages for a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getForwardedMessagesForUser(int $userId): Collection
    {
        try {
            return Message::Forwarded()
                ->where('receiver_id', $userId)
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
     * Mark a specific message as read.
     *
     * @param int $messageId
     * @return bool
     */
    public function markMessageAsRead(int $messageId): bool
    {
        try {
            $message = Message::find($messageId);

            if ($message) {
                $message->markAsRead();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [markMessageAsRead] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

    /**
     * Delete a message by its ID.
     *
     * @param int $messageId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteMessage(int $messageId): ?bool
    {
        try {
            $message = Message::find($messageId);

            if ($message) {
                return $message->delete();
            }

            return false;
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: [deleteMessage] ' . $e->getMessage());
            throw new \Exception(__('messages.unexpected_error'));
        }
    }

}
