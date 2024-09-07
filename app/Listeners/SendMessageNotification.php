<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;


class SendMessageNotification
{
    protected $messaging;
    protected $storage;

    /**
     * Create the event listener.
     *
     * @param Messaging $messaging
     * @return void
     */
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
        $this->storage = (new Factory)
            ->withServiceAccount(config('firebase.service_account'))
            ->createStorage();
    }

    /**
     * @param MessageSent $event
     * @return void
     * @throws \Kreait\Firebase\Exception\FirebaseException
     * @throws \Kreait\Firebase\Exception\MessagingException
     */
    public function handle(MessageSent $event)
    {
        $message = $event->message;

        if (!$message->recipient_token) {
            app('log')->error('Recipient token is missing.');
            return;
        }

        try {
            // Send message to Firebase
            $this->messaging->send([
                'message' => [
                    'token' => $message->recipient_token, // Specify the recipient token
                    'notification' => [
                        'title' => $message->title ?? 'No title',
                        'body' => $message->content ?? 'No content',
                    ],
                ],
            ]);
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            app('log')->error('Error sending notification: ' . $e->getMessage());
        } catch (\Exception $e) {
            app('log')->error('Unexpected error: ' . $e->getMessage());
        }
    }

}
