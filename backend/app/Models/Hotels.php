<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Hotels extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';  
    protected $primaryKey = 'hotels_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'hotels_id',
        'name', 'address', 'city', 'tax_id', 'total_rooms', 'created_at', 'updated_at'
    ];

    protected $guarded = ['hotels_id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->hotels_id = (string) Str::uuid();
        });
    }

    public function hotelRooms(): HasMany
    {
        return $this->hasMany(HotelRooms::class, 'hotel_id', 'hotels_id');
    }
}