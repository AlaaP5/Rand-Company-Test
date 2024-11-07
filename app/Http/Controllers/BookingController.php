<?php

namespace App\Http\Controllers;

use App\DTOs\BookingDTO;
use App\Helpers\LogHelper;
use App\Http\Requests\BookingValidate;
use App\Services\BookingService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    use ApiResponse;

    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    public function createBooking(BookingValidate $request)
    {
        DB::beginTransaction();
        try {
            $bookingDTO = BookingDTO::fromArray($request->validated());
            $this->bookingService->createBooking($bookingDTO);

            DB::commit();

            return $this->successResponse([], 'The Booking is added Successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();

            LogHelper::logError('create_booking', 'Booking failed due to exception', [
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'input_data' => $request->all()
            ]);
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    public function destroyBooking(int $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->bookingService->destroyBooking($id);

            if($result) {
                DB::commit();
                return $this->successResponse([], 'Booking cancelled successfully', 200);

            } else {
                DB::rollBack();
                return $this->badRequestResponse('Booking not available for cancellation', 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            LogHelper::logError('cancel_booking', 'Booking cancellation failed due to exception', [
                'error_message' => $e->getMessage(),
                'booking_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
