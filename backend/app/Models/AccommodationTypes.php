<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AccommodationTypes extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';  
    protected $primaryKey = 'accommodation_types_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'accommodation_types_id',
        'name', 'created_at', 'updated_at'
    ];

    protected $guarded = ['accommodation_types_id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->accommodation_types_id = (string) Str::uuid();
        });
    }

    public function hotelRooms(): HasMany
    {
        return $this->hasMany(HotelRooms::class, 'accommodation_type_id', 'accommodation_types_id');
    }

    public function roomAccommodationRules(): HasMany
    {
        return $this->hasMany(RoomAccommodationRules::class, 'accommodation_type_id', 'accommodation_types_id');
    }
}