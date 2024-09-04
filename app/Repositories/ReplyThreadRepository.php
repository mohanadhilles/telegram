<?php

namespace App\Repositories;

use App\Models\ReplyThread;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReplyThreadRepository implements ReplyThreadInterface
{
    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        try {
            return ReplyThread::create($data);
        } catch (\Exception $e) {
            app('log')->error('Error creating ReplyThread: ' . $e->getMessage());
            throw new \Exception(__('messages.reply_thread_creation_failed'));
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id)
    {
        try {
            return ReplyThread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            app('log')->error('ReplyThread not found: ' . $e->getMessage());
            throw new \Exception(__('messages.reply_thread_not_found'));
        }
    }
}
