<?php

namespace App\Http\Controllers;

use App\DTOs\DestinationDTO;
use App\Enums\AuthCases;
use App\Http\Requests\DestinationValidate;
use App\Http\Requests\UpdateDestinationValidate;
use App\Http\Resources\DestinationResource;
use App\Http\Resources\TripResource;
use App\Services\DestinationService;
use App\Traits\ApiResponse;

class DestinationController extends Controller
{
    use ApiResponse;

    public function __construct(protected DestinationService $destinationService) {}


    public function indexOfDestination()
    {
        try {
            $destinations = $this->destinationService->indexOfDestination();
            return $this->successResponse($destinations, AuthCases::Get_destinations_success->value, 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function createDestination(DestinationValidate $request)
    {
        try {
            $destinationDTO = DestinationDTO::fromArray($request->validated());
            $this->destinationService->createDestination($destinationDTO);
            return $this->successResponse([], 'Destination created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function showDestination(int $id)
    {
        try {
            $destination = $this->destinationService->showDestination($id);
            return $this->successResponse(new DestinationResource($destination), 'Operation successful', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateDestination(UpdateDestinationValidate $request)
    {
        try {
            $destinationDTO = DestinationDTO::fromArray($request->validated());
            $destination = $this->destinationService->updateDestination($destinationDTO);
            return $this->successResponse(new DestinationResource($destination), 'Updated successful', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroyDestination(int $id)
    {
        try {
            $result = $this->destinationService->destroyDestination($id);
            if ($result) {
                return $this->successResponse([], 'Destination deleted successfully', 200);
            } else {
                return $this->forbiddenResponse('Can not delete This destination because has trips');
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function tripsOfDestination(int $id)
    {
        try {
            $destination = $this->destinationService->tripsOfDestination($id);

            if ($destination) {
                return $this->successResponse(TripResource::collection($destination->trips), 'Operation successful, 200');
            } else {
                return $this->errorResponse('not found trips', 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
