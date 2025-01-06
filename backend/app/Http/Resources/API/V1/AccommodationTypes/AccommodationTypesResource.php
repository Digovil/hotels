<?php

namespace App\Http\Resources\API\V1\AccommodationTypes;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AccommodationTypesResource extends JsonResource
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
            'type' => 'accommodation_types',
            'id' => $this->resource->accommodation_types_id,
            'attribute' => [
                'name' => $this->resource->name,
                'created_at' =>  Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at)->format('d/m/Y H:i'),
            ]
        ];
    }
}
