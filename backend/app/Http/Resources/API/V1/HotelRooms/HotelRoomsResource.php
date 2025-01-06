<?php

namespace App\Http\Resources\API\V1\HotelRooms;

use App\Http\Resources\API\V1\AccommodationTypes\AccommodationTypesResource;
use App\Http\Resources\API\V1\Hotels\HotelsResource;
use App\Http\Resources\API\V1\RoomTypes\RoomTypesResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HotelRoomsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'hotel_rooms',
            'id' => $this->resource->hotel_rooms_id,
            'attribute' => [
                'hotel' => new HotelsResource($this->whenLoaded('hotel')),
                'room_type' => new RoomTypesResource($this->whenLoaded('roomType')),
                'accommodation_type' => new AccommodationTypesResource($this->whenLoaded('accommodationType')),
                'quantity' => $this->resource->quantity,
                'created_at' =>  Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at)->format('d/m/Y H:i'),
            ]
        ];
    }
}
