<?php

namespace App\Repositories;

use App\Enums\TripCases;
use App\Helpers\DateNow;
use App\Helpers\LogHelper;
use App\Http\Resources\TripResource;
use App\Interfaces\TripRepositoryInterface;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TripRepository implements TripRepositoryInterface
{

    public function indexOfTrip(array $input)
    {

        $cacheKey = 'trips_' . md5(json_encode($input));

        $trips = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($input) {
            return Trip::with('destination:id,name')
                ->when($input['destination'], function ($query, $destination) {
                    $query->whereHas('destination', function ($q) use ($destination) {
                        $q->where('name', 'like', '%' . $destination . '%');
                    });
                })
                ->when($input['start_date'] && $input['end_date'], function ($query) use ($input) {
                    $query->whereBetween('start_date', [$input['start_date'], $input['end_date']]);
                })
                ->when($input['available_seats'], function ($query, $seats) {
                    $query->where('available_seats', '>=', $seats);
                })
                ->paginate(10);
        });

        if ($trips->isEmpty()) {
            return false;
        }

        return $this->transformTrips($trips);
    }


    public function createTrip(array $input)
    {
            $input['start_date'] = DateNow::presentTime($input['start_date']);
            $input['end_date'] = DateNow::presentTime($input['end_date']);
            $trip = Trip::create($input);

            Cache::flush();

            LogHelper::logInfo('create_trip', 'Trip creation successful',  [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'input_data' => $input
            ]);
            return true;
    }


    public function showTrip($id)
    {
        $trip = Trip::findOrFail($id);
        return $trip;
    }


    public function updateTrip(array $newData)
    {
            $trip = Trip::findOrFail($newData['trip_id']);

            $trip->update([
                'destination_id' => ($newData['destination_id']) ? $newData['destination_id'] : $trip->destination_id,
                'price' => ($newData['price']) ? $newData['price'] : $trip->price,
                'available_seats' => ($newData['available_seats']) ? $newData['available_seats'] : $trip->available_seats,
                'start_date' => ($newData['start_date']) ? DateNow::presentTime($newData['start_date']) : $trip->start_date,
                'end_date' => ($newData['end_date']) ? DateNow::presentTime($newData['end_date']) : $trip->end_date,
            ]);

            Cache::flush();

            LogHelper::logInfo('update_trip', 'Trip update successful', [
                'trip_id' => $trip->id,
                'user_id' => Auth::id(),
                'updated_data' => $newData
            ]);

            return $trip;
    }


    public function destroyTrip($id)
    {
        $trip = Trip::findOrFail($id);

        $dateNow = DateNow::presentTime(now());
        if ($dateNow < $trip->start_date || $trip->statusTrip === TripCases::Completed->value) {

            $trip->bookings()->delete();
            $trip->delete();

            Cache::flush();

            return true;

        } else {
            return false;
        }
    }


    public function transformTrips($trips): array
    {
        return [
            'data' => TripResource::collection($trips->items()),
            'pagination' => [
                'total' => $trips->total(),
                'count' => $trips->count(),
                'per_page' => $trips->perPage(),
                'current_page' => $trips->currentPage(),
                'total_pages' => $trips->lastPage(),
                'next_page_url' => $trips->nextPageUrl(),
                'prev_page_url' => $trips->previousPageUrl(),
            ]
        ];
    }
}
