<?php

namespace App\Http\Resources\Cme;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\ResourcePaginationTrait;

class CmeCollection extends ResourceCollection
{
    use ResourcePaginationTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $collection = [
            'data'      => CmeResource::collection($this->collection),
        ];
        if (!$request->has('nopagination')) {
            $pagination = $this->getPagination();
            return array_merge($collection, $pagination);
        }
        return $collection;

    }
}
