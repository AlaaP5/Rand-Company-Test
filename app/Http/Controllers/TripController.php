<?php

namespace App\Http\Controllers;

use App\DTOs\FilterTripDTO;
use App\DTOs\TripDTO;
use App\Helpers\LogHelper;
use App\Http\Requests\FilterTripValidate;
use App\Http\Requests\TripValidate;
use App\Http\Requests\UpdateTripValidate;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    use ApiResponse;

    protected TripService $tripService;
    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }


    public function indexOfTrip(FilterTripValidate $request)
    {
        try {
            $tripDTO = FilterTripDTO::fromArray($request->validated());
            $trips = $this->tripService->indexOfTrip($tripDTO);

            if ($trips) {
                return $this->successResponse($trips, 'Operation successful', 200);
            } else {
                return $this->errorResponse('not found any Trip', 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function createTrip(TripValidate $request)
    {
        try {
            $tripDTO = TripDTO::fromArray($request->validated());
            $this->tripService->createTrip($tripDTO);
            return $this->successResponse([], 'The Trip is added Successfully', 201);
        } catch (\Exception $e) {

            LogHelper::logError('create_trip', 'Trip creation failed due to exception', [
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'input_data' => $request->all()
            ]);
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function showTrip(int $id)
    {
        try {
            $trip = $this->tripService->showTrip($id);
            return $this->successResponse(new TripResource($trip), 'Operation successful', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateTrip(UpdateTripValidate $request)
    {
        try {
            $tripDTO = TripDTO::fromArray($request->validated());
            $trip = $this->tripService->updateTrip($tripDTO);

            return $this->successResponse(new TripResource($trip), 'Trip updated successfully', 200);
        } catch (\Exception $e) {

            LogHelper::logError('update_trip', 'Trip update failed due to exception', [
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'updated_data' => $request
            ]);

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroyTrip(int $id)
    {
        try {
            $result = $this->tripService->destroyTrip($id);
            if ($result) {
                return $this->successResponse([], 'Trip has been deleted successfully', 200);
            } else {
                return $this->forbiddenResponse('you can not delete this a trip', 403);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
