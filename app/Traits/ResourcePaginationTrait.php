<?php

namespace App\Traits;

/**
 * Trait ResourcePaginationTrait
 * @package App\Traits
 */
trait ResourcePaginationTrait
{
    /**
     * get a pagination for the resource collection
     * @return mixed
     */
    public function getPagination()
    {
        return [
            'total'             => $this->total(),
            'count'             => $this->count(),
            'per_page'          => $this->perPage(),
            'current_page'      => $this->currentPage(),
            'total_pages'       => $this->lastPage(),
            'page_url'          => $this->path(),
            'next_page_url'     => $this->nextPageUrl(),
            'previous_page_url' => $this->previousPageUrl(),
        ];
    }
}
