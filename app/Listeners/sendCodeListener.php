<?php

namespace App\Listeners;

use App\Events\CreateUserEvent;
use App\Interfaces\Application\IUserManagementRepository;


class sendCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected IUserManagementRepository $auth) {}

    /**
     * Handle the event.
     */
    public function handle(CreateUserEvent $event): void
    {
        $this->auth->sendCode($event->user);
    }
}
