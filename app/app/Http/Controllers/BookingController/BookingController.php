<?php

namespace App\Http\Controllers\BookingController;

use App\DataAdapters\BookingDataAdapter\BookingDataAdaptor;
use App\Services\BookingService\BookingService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookingController
{
    public function __construct(
        private readonly BookingService     $bookingService,
        private readonly BookingDataAdaptor $bookingDataAdaptor
    ) {}

    public function createBooking(Request $request): JsonResponse
    {
        try {
            return getSuccessResponse(
                $this->bookingService->createBooking(
                    $this->bookingDataAdaptor->createBookingDTOByRequest($request)
                )
            );
        } catch (\Exception $e) {
            return getSuccessResponse($e->getMessage());
        }
    }

    public function getBookingsForResource(int $id): JsonResponse
    {
        try {
            return getSuccessResponse(
                $this->bookingService->getBookings($id)
            );
        } catch (\Exception $e) {
            return getSuccessResponse($e->getMessage());
        }
    }

    public function deleteBooking(int $id): JsonResponse
    {
        try {
            return getSuccessResponse(
                $this->bookingService->deleteBookings($id)
            );
        } catch (\Exception $e) {
            return getSuccessResponse($e->getMessage());
        }
    }
}
