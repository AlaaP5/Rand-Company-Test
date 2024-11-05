<?php

namespace App\Repositories;

use App\Events\AddBookingEvent;
use App\Events\CancelBookingEvent;
use App\Helpers\DateNow;
use App\Helpers\LogHelper;
use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;


class BookingRepository implements BookingRepositoryInterface
{

    public function createBooking(array $input)
    {

        $trip = Trip::findOrFail($input['trip_id']);

        $input['user_id'] = Auth::id();
        Booking::create($input);

        Event::dispatch(new AddBookingEvent($trip, $input['seats_booked']));

        LogHelper::logInfo('create_booking', 'Booking successful', $input);
        return true;
    }


    public function destroyBooking(int $id)
    {

        $book = Booking::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

        $dateNow = DateNow::presentTime(now());
        $trip = Trip::findOrFail($book->trip_id);


        if ($trip->start_date <= $dateNow && $trip->end_date >= $dateNow) {

            $book->delete();

            Event::dispatch(new CancelBookingEvent($trip, $book->seats_booked));

            LogHelper::logInfo('cancel_booking', 'Booking cancelled successfully', [
                'user_id' => Auth::id(),
                'trip_id' => $book->trip_id,
                'seats_cancelled' => $book->seats_booked
            ]);

            return true;
        }

        return false;
    }
}
