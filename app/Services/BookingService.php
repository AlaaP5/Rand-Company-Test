<?php

namespace App\Services;

use App\DTOs\BookingDTO;
use App\Interfaces\BookingRepositoryInterface;


class BookingService
{
    protected $bookingRepository;
    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }


    public function createBooking(BookingDTO $bookingDTO)
    {
        return $this->bookingRepository->createBooking($bookingDTO->toArray());
    }

    public function destroyBooking(int $id)
    {
        return $this->bookingRepository->destroyBooking($id);
    }
}
