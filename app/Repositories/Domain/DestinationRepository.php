<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IDestinationRepository;
use App\Models\Destination;


class DestinationRepository implements IDestinationRepository
{

    public function get_all_destinations()
    {
        $destinations = Destination::paginate(10);
        return $destinations;
    }


    public function createDestination(array $data)
    {
        $destination = Destination::create($data);
        return $destination;
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
