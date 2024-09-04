<?php

namespace App\Http\Controllers\Api\v1\Broadcasr;

use App\Http\Controllers\Controller;
use App\Service\Broadcast\BroadcastService;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    protected $broadcastService;

    public function __construct(BroadcastService $broadcastService)
    {
        $this->broadcastService = $broadcastService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBroadcast(Request $request)
    {
      $request->validate([
          'sender_id' => 'required|exists:users,id',
          'content' => 'required|string',
          'media_type' => 'nullable|string',
          'media_url' => 'nullable|string',
          'recipients' => 'required|array',
          'recipients.*' => 'exists:users,id',
      ]);

        try {
            $broadcastMessage = $this->broadcastService->sendBroadcastMessage(
                $request->sender_id,
                $request->content,
                $request->media_type,
                $request->media_url,
                $request->recipients
            );

            return response()->json(['message' => 'Broadcast message sent successfully', 'broadcast' => $broadcastMessage], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('messages.broadcast_failed')], 500);
        }
    }
}
