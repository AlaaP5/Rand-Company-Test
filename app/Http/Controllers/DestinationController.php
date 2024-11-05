<?php

namespace App\Http\Controllers;

use App\DTOs\DestinationDTO;
use App\Http\Requests\DestinationValidate;
use App\Http\Requests\UpdateDestinationValidate;
use App\Http\Resources\DestinationResource;
use App\Http\Resources\TripResource;
use App\Services\DestinationService;


class DestinationController extends Controller
{
    protected DestinationService $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }


    public function indexOfDestination()
    {
        try {
            $destinations = $this->destinationService->indexOfDestination();

            return response()->json([
                'data' => DestinationResource::collection($destinations->items()),
                'pagination' => [
                    'current_page' => $destinations->currentPage(),
                    'total_pages' => $destinations->lastPage(),
                    'total_items' => $destinations->total(),
                    'per_page' => $destinations->perPage(),
                    'first_page_url' => $destinations->url(1),
                    'last_page_url' => $destinations->url($destinations->lastPage())
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createDestination(DestinationValidate $request)
    {
        try {
            $destinationDTO = DestinationDTO::fromArray($request->validated());
            $this->destinationService->createDestination($destinationDTO);
            return response()->json(['message' => 'Destination created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function showDestination(int $id)
    {
        try {
            $destination = $this->destinationService->showDestination($id);
            return response()->json(['data' => new DestinationResource($destination)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateDestination(UpdateDestinationValidate $request)
    {
        try {
            $destinationDTO = DestinationDTO::fromArray($request->validated());
            $destination = $this->destinationService->updateDestination($destinationDTO);
            return response()->json(['data' => new DestinationResource($destination)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroyDestination(int $id)
    {
        try {
            $result = $this->destinationService->destroyDestination($id);
            if ($result) {
                return response()->json(['message' => 'Destination deleted successfully'], 201);
            } else {
                return response()->json(['message' => 'Can not delete This destination because has trips'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function tripsOfDestination(int $id)
    {
        try {
            $destination = $this->destinationService->tripsOfDestination($id);

            if ($destination) {
                return response()->json(['data' => TripResource::collection($destination->trips)], 200);
            } else {
                return response()->json(['data' => 'not found trips'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
