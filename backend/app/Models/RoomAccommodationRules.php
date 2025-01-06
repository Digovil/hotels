<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RoomAccommodationRules extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';  
    protected $primaryKey = 'room_accommodation_rules_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'room_accommodation_rules_id',
        'room_type_id', 'accommodation_type_id', 'created_at', 'updated_at'
    ];

    protected $guarded = ['room_accommodation_rules_id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->room_accommodation_rules_id = (string) Str::uuid();
        });
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