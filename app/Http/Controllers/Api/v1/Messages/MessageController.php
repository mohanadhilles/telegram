<?php

namespace App\Http\Controllers\Api\v1\Messages;

use App\Http\Controllers\Controller;
use App\Libraries\Helpers;
use App\Service\FireBase\ChatUpdates;
use App\Service\FireBase\FirebaseStorageService;
use App\Service\Messages\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class MessageController extends Controller
{

    protected $messageService;
    protected $firebaseStorageService;

    protected const PATH_FILES = 'messages';

    public function __construct(
        MessageService         $messageService,
        FirebaseStorageService $firebaseStorageService
    )
    {
        $this->messageService = $messageService;
        $this->firebaseStorageService = $firebaseStorageService;

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'receiver_id' => 'required|integer|exists:users,id',
                'group_id' => 'nullable|exists:groups,id',
                'content' => 'nullable|string',
                'media_file' => 'required',
                'is_read' => 'boolean',
                'is_forwarded' => 'boolean',
                'reply_to_message_id' => 'nullable|exists:messages,id',
                'recipient_token' => 'nullable|string',

            ]);
            $validatedData['receiver_id'] = intval($request->receiver_id);
            $validatedData['sender_id'] = auth('api')->user()->id;
            $validatedData['recipient_token'] = $request->recipient_token ? $request->recipient_token : Hash::make(rand(11111, 9999999));

            if ($request->hasFile('media_file')) {
                $mimeType = $request->file('media_file')->getMimeType();
                $mediaType = Helpers::getMediaTypeFromMimeType($mimeType);
                $file = $request->file('media_file');
                $firebaseUrl = $this->firebaseStorageService->handle($file, $mediaType, self::PATH_FILES);
                $validatedData['media_url'] = $firebaseUrl;
                $validatedData['media_type'] = $mediaType;
            }

            $message = $this->messageService->createMessage($validatedData);
            return response()->json(['message' => __('messages.message_created'), 'data' => $message], config('request.rsp_created'));

        } catch (ValidationException $e) {
            app('log')->error($e->getMessage());
            return response()->json(['errors' => $e->getMessage()], config('request.rsp_invalid_params'));
        } catch (\Exception $e) {
            app('log')->error($e->getMessage());
            return response()->json(['error' => __('messages.error_occurred')], config('request.rsp_error_occurred'));
        }
    }

    /**
     * Get a message by its ID.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $request->validate([
                'receiver_id' => 'required|exists:users,id',
            ]);

            $receiverId = $request->get('receiver_id');
            $userId = auth('api')->id();

            $receiver = $this->messageService->getUserById($receiverId);
            $message = $this->messageService->getMessageById($userId, $receiverId);

            if (!$message) {
                return response()->json(['error' => __('messages.message_not_found')], config('request.rsp_not_found'));
            }
            return response()->json(['user' => $receiver, 'messages' => $message], config('request.rsp_success'));

        } catch (\Exception $e) {
            return response()->json(['error' => __('messages.error_occurred')], config('request.rsp_error_occurred'));
        }
    }

    /**
     * Get messages for a specific user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessagesForUser()
    {
        try {
            $userId = auth('api')->id();
            $messages = $this->messageService->getMessagesForUser($userId);
            return response()->json(['data' => $messages], config('request.rsp_success'));

        } catch (\Exception $e) {
            return response()->json(['error' => __('messages.error_occurred')], config('request.rsp_error_occurred'));
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateTypingStatus(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'is_typing' => 'required|boolean',
        ]);

        $user = $request->user();
        $this->messageService->updateUserById($user, $request->all());
        return response()->json(['message' => __('messages.message_typing_success')], config('request.rsp_success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateSeenStatus(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'is_read' => 'required|boolean',
        ]);

        $user = $request->user();
        $receiverId = $request->receiver_id;
        $this->messageService->seenMessage($user, $receiverId, $request->all());

        return response()->json(['message' => __('messages.message_typing_success')], config('request.rsp_success'));
    }
}
