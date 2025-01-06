<?php

namespace App\Http\Resources\API\V1\Hotels;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HotelsResource extends JsonResource
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
            'type' => 'hotels',
            'id' => $this->resource->hotels_id,
            'attribute' => [
                'name' => $this->resource->name,'address' => $this->resource->address,'city' => $this->resource->city,'tax_id' => $this->resource->tax_id,'total_rooms' => $this->resource->total_rooms,'created_at' =>  Carbon::createFromFormat('Y-m-d H:i:s', $this->resource->created_at)->format('d/m/Y H:i'),
            ]
        ];
    }
}
