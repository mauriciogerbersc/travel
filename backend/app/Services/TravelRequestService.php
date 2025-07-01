<?php

namespace App\Services;

use App\Models\TravelRequestModel;
use Illuminate\Support\Facades\Auth;

class TravelRequestService
{
    public function list($filters = [])
    {
        $user = Auth::user();

        $query = $user->role === 'admin'
            ? TravelRequestModel::query()
            : TravelRequestModel::where('user_id', $user->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['destination'])) {
            $query->where('destination', 'like', '%' . $filters['destination'] . '%');
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('departure_date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->get();
    }

    public function updateStatus($id, $newStatus)
    {
        $travelRequest = TravelRequestModel::findOrFail($id);

        if ($newStatus === 'cancelado' && $travelRequest->status === 'aprovado') {
            throw new \Exception('Cannot cancel an approved request.');
        }

        $travelRequest->status = $newStatus;
        $travelRequest->save();

        return $travelRequest;
    }

    public function create($data): TravelRequestModel
    {
        return TravelRequestModel::create([
            'user_id' => Auth::id(),
            ...$data,
            'status' => 'solicitado',
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();

        $travelRequest = $user->role == 'admin'
            ? TravelRequestModel::query()
            : TravelRequestModel::where('user_id', $user->id);

        return $travelRequest->where('id', $id)
            ->firstOrFail();
    }

    public function cancelUserRequest(TravelRequestModel $request)
    {
        if ($request->status === 'aprovado') {
            throw new \Exception('Cannot cancel an approved request.');
        }

        $request->status = 'cancelado';
        $request->save();

        return $request;
    }
}
