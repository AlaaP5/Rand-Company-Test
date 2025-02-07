<?php

namespace App\Providers;

use App\Interfaces\Application\IUserManagementRepository;
use App\Interfaces\Domain\IUserRepository;
use App\Interfaces\Domain\IBookingRepository;
use App\Interfaces\Domain\IDestinationRepository;
use App\Interfaces\Domain\ITripRepository;
use App\Repositories\Application\UserManagementRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Domain\BookingRepository;
use App\Repositories\Domain\DestinationRepository;
use App\Repositories\Domain\TripRepository;

;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ITripRepository::class, TripRepository::class);
        $this->app->bind(IDestinationRepository::class, DestinationRepository::class);
        $this->app->bind(IBookingRepository::class, BookingRepository::class);

        $this->app->bind(IUserManagementRepository::class, UserManagementRepository::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
