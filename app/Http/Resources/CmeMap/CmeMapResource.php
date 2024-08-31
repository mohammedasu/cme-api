<?php

namespace App\Http\Resources\CmeMap;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cme\CmeResource;

class CmeMapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'cme_id'            => $this->cme_id,
            'map_type'          => $this->map_type,
            'map_type_id'       => $this->map_type_id,
            'when_to_show'      => $this->when_to_show,
        ];
    }
}
