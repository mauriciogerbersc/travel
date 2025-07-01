<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelRequestModel extends Model
{
    use HasFactory;
    
    protected $table = 'travel_request';

    protected $fillable = [
        'user_id',
        'destination',
        'departure_date',
        'return_date',
        'departure_date',
        'status',
        'requester_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
