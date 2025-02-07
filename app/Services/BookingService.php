<?php

namespace App\Services;

use App\DTOs\BookingDTO;
use App\Interfaces\Domain\IBookingRepository;


class BookingService
{
    public function __construct(protected IBookingRepository $bookingRepository) {}


    public function createBooking(BookingDTO $bookingDTO)
    {
        return $this->bookingRepository->createBooking($bookingDTO->toArray());
    }

    public function destroyBooking(int $id)
    {
        return $this->bookingRepository->destroyBooking($id);
    }
}
