<?php

namespace App\Services;

use App\DTOs\DestinationDTO;
use App\Http\Resources\DestinationResource;
use App\Interfaces\Domain\IDestinationRepository;
use App\Traits\ApiResponse;

class DestinationService
{

    public function __construct(protected IDestinationRepository $destinationRepository) {}

    use ApiResponse;

    public function indexOfDestination()
    {
        $destinations = $this->destinationRepository->get_all_destinations();

        if(count($destinations)) {

            $data = DestinationResource::collection($destinations);
            return $this->transformation($data);
        }
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
