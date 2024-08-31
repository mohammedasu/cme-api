<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\MemberPointHistory;

class MemberPointHistoryRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MemberPointHistory();
    }

    public function findByMultipleFields($where_clause, $multiple = false)
    {
        $memberHistoryPoint = $this->model->query();
        if ($multiple) {
            return $memberHistoryPoint->where($where_clause)->paginate(Constants::PAGINATION_LENGTH);
        } else {
            return $memberHistoryPoint->where($where_clause)->first();
        }
    }

    public function store($data)
    {
        return $this->model->create($data);
    }
}
