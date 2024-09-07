<?php

namespace App\Service\Messages;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Receiver;
use App\Models\User;
use App\Repositories\MessageInterface;
use App\Service\FireBase\ChatUpdates;
use Carbon\Carbon;

class MessageService
{


    protected const IS_TYPING = "typing";
    protected const IS_SEEN = "seen";
    private const IS_READIED = 1;


    protected $messageRepo;

    public function __construct(MessageInterface $messageRepo)
    {
        $this->messageRepo = $messageRepo;
    }

    /**
     * Create a new message.
     *
     * @param array $data
     * @return Message
     * @throws \Exception
     */
    public function createMessage(array $data)
    {
        self::creataChat($data);
        self::createReceiver($data['receiver_id']);
        return $this->messageRepo->createMessage($data);
    }

    /**
     * Retrieve a message by its ID.
     * @param int $userId
     * @param int $receiverId
     * @return Message
     * @throws \Exception
     */
    public function getMessageById(int $userId, int $receiverId)
    {
        $this->seenMessage(
            $userId,
            $receiverId,
            ['is_read' => self::IS_READIED]);

        return Chat::with(['receiver:name,photo'])
            ->where('sender_id', $userId)
            ->where('receiver_id', $receiverId)
            ->orderBy('_id','desc')
            ->simplePaginate(config('settings.pagination'));
//            $this->messageRepo->getMessageById($userId, $receiverId);
    }

    /**
     * Retrieve messages for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getMessagesForUser(int $userId)
    {
        return $this->messageRepo->getMessagesForUser($userId);
    }

    /**
     * @param $senderId
     * @param $receiverId
     * @param $data
     * @throws \Exception
     */
    public function seenMessage($senderId, $receiverId, $data)
    {
        app(ChatUpdates::class)->handle($senderId, $receiverId, self::IS_SEEN);
        $this->messageRepo->updateMessage($senderId, $receiverId, $data);
    }


    /**
     * @param $userId
     * @return mixed
     */
    public function getUserById($userId): mixed
    {
        return User::find($userId);
    }


    /**
     * @param $user
     * @param $data
     * @return void
     * @throws \Exception
     */
    public function updateUserById($user, $data)
    {

        $user->is_typing = $data['is_typing'];
        $receiverId = $data['receiver_id'];
        $user->save();
        if ($data['is_typing']) {
            app(ChatUpdates::class)->handle($user->id, $receiverId, self::IS_TYPING);
        } else {
            app(ChatUpdates::class)->handle($user->id, $receiverId, self::IS_TYPING);
        }
    }

    /**
     * @param $data
     * @return void
     */
    private static function creataChat($data)
    {
        $data['created_at'] = Carbon::now()->format('D, M j, Y h:i A');
        $data['updated_at'] = Carbon::now()->format('D, M j, Y h:i A');
        Chat::create($data);
    }

    /**
     * @param $userId
     * @return void
     */
    private static function createReceiver($userId)
    {
        $user = User::find($userId);
        Receiver::create([
            'phone' => $user->phone,
            'photo'=>  $user->photo,
            'name'=> $user->name,
            'email'=> $user->email,
        ]);
    }
}
