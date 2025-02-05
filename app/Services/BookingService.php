<?php

namespace App\Services;

use App\DTOs\BookingDTO;
use App\Interfaces\BookingRepositoryInterface;


class BookingService
{
    public function __construct(protected BookingRepositoryInterface $bookingRepository) {}


    public function createBooking(BookingDTO $bookingDTO)
    {
        return $this->bookingRepository->createBooking($bookingDTO->toArray());
    }

    public function destroyBooking(int $id)
    {
        return $this->bookingRepository->destroyBooking($id);
    }
}
