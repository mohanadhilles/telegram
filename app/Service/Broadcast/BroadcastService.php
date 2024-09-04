<?php

namespace App\Service\Broadcast;

use App\Models\BroadcastMessage;
use App\Models\BroadcastRecipient;
use Illuminate\Support\Facades\DB;

class BroadcastService
{

    /**
     * @param $senderId
     * @param $content
     * @param $mediaType
     * @param $mediaUrl
     * @param $recipientIds
     * @return mixed
     * @throws \Exception
     */
    public function sendBroadcastMessage($senderId, $content, $mediaType = null, $mediaUrl = null, $recipientIds = [])
    {
        try {
            DB::beginTransaction();

            $broadcastMessage = BroadcastMessage::create([
                'sender_id' => $senderId,
                'content' => $content,
                'media_type' => $mediaType,
                'media_url' => $mediaUrl,
            ]);

            $recipientsData = [];
            foreach ($recipientIds as $userId) {
                $recipientsData[] = [
                    'broadcast_message_id' => $broadcastMessage->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            BroadcastRecipient::insert($recipientsData);

            DB::commit();

            return $broadcastMessage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
