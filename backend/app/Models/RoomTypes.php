<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RoomTypes extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';  
    protected $primaryKey = 'room_types_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'room_types_id',
        'name', 'created_at', 'updated_at'
    ];

    protected $guarded = ['room_types_id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->room_types_id = (string) Str::uuid();
        });
    }

    public function hotelRooms(): HasMany
    {
        return $this->hasMany(HotelRooms::class, 'room_type_id', 'room_types_id');
    }

    public function roomAccommodationRules(): HasMany
    {
        return $this->hasMany(RoomAccommodationRules::class, 'room_type_id', 'room_types_id');
    }
}