<?php

namespace App\Http\Controllers\Api\v1\Messages;

use App\Http\Controllers\Controller;
use App\Service\Messages\ReplyThreadService;
use Illuminate\Http\Request;

class ReplyThreadController extends Controller
{
    protected $service;

    public function __construct(ReplyThreadService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
       $request->validate([
           'message_id' => 'required|exists:messages,id',
           'reply_message_id' => 'required|exists:messages,id',
       ]);

        try {
            $replyThread = $this->service->createReplyThread($request->all());
            return response()->json(['message' => __('messages.reply_thread_created'), 'data' => $replyThread], config('request.rsp_created'));
        } catch (\Exception $e) {
            app('log')->error('Error in ReplyThreadController: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], config('request.rsp_error_occurred'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $request->validate([
               'message_id' => 'required|exists:messages,id',
            ]);
            $messageId = $request->message_id;
            $replyThread = $this->service->getReplyThreadById($messageId);

            return response()->json(['data' => $replyThread], config('request.rsp_success'));
        } catch (\Exception $e) {
            app('log')->error('Error in ReplyThreadController: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], config('request.rsp_not_found'));
        }
    }
}
