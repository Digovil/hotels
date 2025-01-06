<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HotelRooms extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';  
    protected $primaryKey = 'hotel_rooms_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'hotel_rooms_id',
        'hotel_id', 'room_type_id', 'accommodation_type_id', 'quantity', 'created_at', 'updated_at'
    ];

    protected $guarded = ['hotel_rooms_id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->hotel_rooms_id = (string) Str::uuid();
        });
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotels::class, 'hotel_id', 'hotels_id');
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomTypes::class, 'room_type_id', 'room_types_id');
    }

    public function accommodationType(): BelongsTo
    {
        return $this->belongsTo(AccommodationTypes::class, 'accommodation_type_id', 'accommodation_types_id');
    }
}