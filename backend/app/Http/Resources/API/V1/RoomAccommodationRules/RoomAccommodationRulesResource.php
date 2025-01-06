<?php

namespace App\Http\Resources\API\V1\RoomAccommodationRules;

use App\Http\Resources\API\V1\AccommodationTypes\AccommodationTypesResource;
use App\Http\Resources\API\V1\RoomTypes\RoomTypesResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RoomAccommodationRulesResource extends JsonResource
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
            'type' => 'room_accommodation_rules',
            'id' => $this->resource->room_accommodation_rules_id,
            'attribute' => [
                'room_type' => new RoomTypesResource($this->whenLoaded('roomType')),
                'accommodation_type' => new AccommodationTypesResource($this->whenLoaded('accommodationType')),
                'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at)->format('d/m/Y H:i'),
            ]
        ];
    }
}
