<?php

namespace App\Http\Controllers\Api\v1\Messages;

use App\Http\Controllers\Controller;
use App\Service\Messages\ForwardedMessageService;
use Illuminate\Http\Request;

class ForwardedMessageController extends Controller
{
    protected $service;

    public function __construct(ForwardedMessageService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_message_id' => 'required|exists:messages,id',
            'forwarded_to_user_id' => 'required|exists:users,id',
        ]);

        try {
            $forwardedMessage = $this->service->createForwardedMessage($request->all());
            return response()->json(['message' => __('messages.forwarded_message_created'), 'data' => $forwardedMessage], config('request.rsp_created'));
        } catch (\Exception $e) {
            app('log')->error('Error in ForwardedMessageController: ' . $e->getMessage());
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

            $forwardedMessage = $this->service->getForwardedMessageById($messageId);
            return response()->json(['data' => $forwardedMessage], config('request.rsp_success'));
        } catch (\Exception $e) {
            app('log')->error('Error in ForwardedMessageController: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], config('request.rsp_not_found'));
        }
    }
}
