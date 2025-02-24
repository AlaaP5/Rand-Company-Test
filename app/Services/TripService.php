<?php

namespace App\Services;

use App\DTOs\FilterTripDTO;
use App\DTOs\TripDTO;
use App\Interfaces\Domain\ITripRepository;

class TripService
{
    public function __construct(protected ITripRepository $tripRepository) {}


    public function indexOfTrip(FilterTripDTO $tripDTO)
    {
        return $this->tripRepository->indexOfTrip($tripDTO->toArray());
    }

    public function createTrip(TripDTO $tripDTO)
    {
        return $this->tripRepository->createTrip($tripDTO->toArray());
    }

    public function showTrip(int $id)
    {
        return $this->tripRepository->showTrip($id);
    }

    public function updateTrip(TripDTO $tripDTO)
    {
        $tripData = $tripDTO->toArray();
        return $this->tripRepository->updateTrip($tripData);
    }

    public function destroyTrip(int $id)
    {
        return $this->tripRepository->destroyTrip($id);
    }
}
