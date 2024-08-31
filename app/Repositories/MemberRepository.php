<?php

namespace App\Repositories;

use App\Models\Member;
use App\Constants\Constants;

class MemberRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Member();
    }

    public function findByMultipleFields($where_clause, $multiple = false, $request=null)
    {
        $member = $this->model->where($where_clause);
        if ($multiple) {
            if($request->has('nopagination')){
                return $member->get();
            }
            return $member->paginate(Constants::PAGINATION_LENGTH);
        } else {
            return $member->where($where_clause)->first();
        }
    }
}
