<?php

namespace App\Repositories;

use App\Models\MemberPoint;
use App\Constants\Constants;

class MemberPointRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MemberPoint();
    }

    public function findByMultipleFields($where_clause, $multiple = false)
    {
        $memberPoint = $this->model->query();
        if ($multiple) {
            return $memberPoint->where($where_clause)->paginate(Constants::PAGINATION_LENGTH);
        } else {
            return $memberPoint->where($where_clause)->first();
        }
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $memberPoint)
    {
        $memberPoint->update($data);
        return $memberPoint->refresh();
    }
}
