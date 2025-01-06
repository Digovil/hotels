<?php

namespace App\Http\Resources\API\V1\RoomTypes;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RoomTypesResource extends JsonResource
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
            'type' => 'room_types',
            'id' => $this->resource->room_types_id,
            'attribute' => [
                'name' => $this->resource->name,'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at)->format('d/m/Y H:i'),
            ]
        ];
    }
}
