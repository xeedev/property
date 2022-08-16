<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            'demand' => $this->demand,
            'sold_in' => $this->sold_in,
            'sold_by'  => $this->sold_by,
            'sold_to'  => $this->sold_to,
            'commission_received'  => $this->commission_received,
            'actual_commission_amount'  => $this->actual_commission_amount,
            'created_at' => $this->created_at,
        ];
    }
}
