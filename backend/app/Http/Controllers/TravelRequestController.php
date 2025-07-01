<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelRequest;
use App\Http\Requests\UpdateTravelRequestStatus;
use App\Models\TravelRequestModel;
use App\Services\TravelRequestService;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    public function __construct(protected TravelRequestService $service) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->service->list($request->only(['status', 'destination', 'start_date', 'end_date']))
        );
    }

    public function store(StoreTravelRequest $request)
    {
        return response()->json($this->service->create($request->validated()), 201);
    }

    public function show(Request $request, $id)
    { 
        $user = $request->user();

        $travelRequest = $user->role === 'admin'
            ? TravelRequestModel::findOrFail($id)
            : TravelRequestModel::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        return response()->json($travelRequest);
    }

    public function updateStatus(UpdateTravelRequestStatus $request, $id)
    {
        try {
            $updated = $this->service->updateStatus($id, $request->status);
            return response()->json($updated, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request, $id)
    {
        $travelRequest = TravelRequestModel::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            return response()->json($this->service->cancelUserRequest($travelRequest), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 405);
        }
    }
}
