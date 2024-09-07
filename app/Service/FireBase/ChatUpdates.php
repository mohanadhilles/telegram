<?php

namespace App\Service\FireBase;

use Kreait\Firebase\Factory;


class ChatUpdates
{
    protected $firebaseDatabase;

    protected const IS_TYPING = 'typing';
    protected const IS_SEEN = "seen";


    public function __construct()
    {
        $this->firebaseDatabase = (new Factory)
            ->withServiceAccount(config('firebase.service_account'))
            ->withDatabaseUri(config('firebase.database_url'))
            ->createDatabase();
    }


    /**
     * @param $userId
     * @param $receiverId
     * @param $type
     * @return void
     * @throws \Exception
     */
    public function handle($userId, $receiverId, $type): void
    {
        try {
            switch ($type) {
                case self::IS_TYPING:
                    $this->firebaseDatabase->getReference('chats/typing/' . 'from/' . $userId . '/to/' . $receiverId)
                        ->set(true);
                    break;
                case self::IS_SEEN:
                    $this->firebaseDatabase->getReference('chats/seen/' . '/from/' . $userId . '/to/' . $receiverId)
                        ->set(true);
            }

        } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
            app('log')->error('ChatUserTyping:[handle] ' . $e->getMessage());
            throw new \Exception(__('lang::messages.error_occurred'));
        }
    }

}
