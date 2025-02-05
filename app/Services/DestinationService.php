<?php

namespace App\Services;

use App\DTOs\DestinationDTO;
use App\Interfaces\DestinationRepositoryInterface;

class DestinationService
{

    public function __construct(protected DestinationRepositoryInterface $destinationRepository) {}

    public function indexOfDestination()
    {
        return $this->destinationRepository->indexOfDestination();
    }

    public function createDestination(DestinationDTO $destinationDTO)
    {
        return $this->destinationRepository->createDestination($destinationDTO->toArray());
    }

    public function showDestination(int $id)
    {
        return $this->destinationRepository->showDestination($id);
    }

    public function updateDestination(DestinationDTO $destinationDTO)
    {
        return $this->destinationRepository->updateDestination($destinationDTO->toArray());
    }

    public function destroyDestination(int $id)
    {
        return $this->destinationRepository->destroyDestination($id);
    }

    public function tripsOfDestination(int $id)
    {
        return $this->destinationRepository->tripsOfDestination($id);
    }
}
