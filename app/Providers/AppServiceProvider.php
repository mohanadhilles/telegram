<?php

namespace App\Providers;

use App\Repositories\ForwardedMessageInterface;
use App\Repositories\ForwardedMessageRepository;
use App\Repositories\MessageInterface;
use App\Repositories\MessageRepository;
use App\Repositories\ReplyThreadInterface;
use App\Repositories\ReplyThreadRepository;
use App\Service\Auth\OtpService;
use App\Service\FireBase\FirebaseStorageService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(FirebaseStorageService::class, function ($app) {
            return new FirebaseStorageService();
        });

        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService();
        });

        $this->app->singleton(Messaging::class, function ($app) {
            $firebase = (new Factory)->withServiceAccount(config('firebase.service_account'));
            return $firebase->createMessaging();
        });


        $this->app->bind(MessageInterface::class,MessageRepository::class);
        $this->app->bind(ForwardedMessageInterface::class,ForwardedMessageRepository::class);
        $this->app->bind(ReplyThreadInterface::class,ReplyThreadRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Lang::setLocale(config('app.locale'));

        $this->loadTranslationsFrom(base_path('lang'), 'lang');


    }
}
