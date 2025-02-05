<?php

namespace App\Listeners;

use App\Events\CreateUserEvent;
use App\Services\AuthService;


class sendCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected AuthService $auth) {}

    /**
     * Handle the event.
     */
    public function handle(CreateUserEvent $event): void
    {
        $this->auth->sendCode($event->user);
    }
}
