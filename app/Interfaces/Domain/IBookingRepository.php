<?php

namespace App\Interfaces\Domain;

interface IBookingRepository
{
    public function createBooking(array $request);
    public function destroyBooking(int $id);
}
