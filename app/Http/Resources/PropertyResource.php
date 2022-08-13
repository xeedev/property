<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'id' => $this->id,
            'property_number' => $this->property_number,
            'detail' => $this->detail,
            'status' => $this->status,
            'location'  => $this->location,
            'block'  => $this->block,
            'sold_by'  => $this->sold_by,
            'sold_to'  => $this->sold_to,
            'media' => $this->media,
            'created_at' => $this->created_at,
        ];
    }
}
