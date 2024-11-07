<?php

namespace App\Repositories;

use App\Http\Resources\DestinationResource;
use App\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;


class DestinationRepository implements DestinationRepositoryInterface
{

    public function indexOfDestination()
    {
        $destinations = Destination::paginate(10);
        return $this->transformDestinations($destinations);
    }


    public function createDestination(array $data)
    {
        $destination = Destination::create($data);
        return true;
    }


    public function showDestination(int $id)
    {
        $destination = Destination::findOrFail($id);
        return $destination;
    }


    public function updateDestination(array $newData)
    {
        $destination = Destination::findOrFail($newData['destination_id']);

        $destination->update([
            'name' => ($newData['name']) ? $newData['name'] : $destination->name
        ]);

        return $destination;
    }


    public function destroyDestination(int $id)
    {
        $destination = Destination::findOrFail($id);

        if ($destination->trips->isEmpty()) {
            $destination->delete();
            return true;
        }
        return false;
    }


    public function tripsOfDestination(int $id)
    {
        $destination = Destination::with('trips')->findOrFail($id);

        if ($destination->trips->isEmpty()) {
            return false;
        }
        return $destination;
    }


    public function transformDestinations($destinations): array
    {
        return [
            'data' => DestinationResource::collection($destinations->items()),
            'pagination' => [
                'total' => $destinations->total(),
                'count' => $destinations->count(),
                'per_page' => $destinations->perPage(),
                'current_page' => $destinations->currentPage(),
                'total_pages' => $destinations->lastPage(),
                'next_page_url' => $destinations->nextPageUrl(),
                'prev_page_url' => $destinations->previousPageUrl(),
            ]
        ];
    }
}
