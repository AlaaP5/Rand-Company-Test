<?php

namespace App\Repositories;

use App\Http\Resources\DestinationResource;
use App\Http\Resources\TripResource;
use App\Interfaces\DestinationRepositoryInterface;
use App\Models\Destination;


class DestinationRepository implements DestinationRepositoryInterface
{

    public function indexOfDestination()
    {
        $destinations = Destination::paginate(10);
        return $destinations;
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
}
