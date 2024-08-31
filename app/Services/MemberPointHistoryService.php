<?php

namespace App\Services;

use App;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\MemberPointHistoryRepository;
use App\Exceptions\CustomErrorException;

class MemberPointHistoryService
{
    public function __construct()
    {
        $this->repository = new MemberPointHistoryRepository();
    }

    public function findByMultipleFields($where_clause, $multiple = false)
    {
        return $this->repository->findByMultipleFields($where_clause, $multiple);
    }
    /**
     * Function to store / update member points and details
    */

    public function store($cmeDetails,$memberId)
    {
        Log::info('MemberPointHistoryService | storeOrUpdate', ['CME Details'=>$cmeDetails,'Member Id'=>$memberId]);
        try {
            $memberPoint = $this->repository->store([
                'member_type'           => 'member',
                'member_id'             => $memberId,
                'content_type'          => 'cme',
                'content_id'            => $cmeDetails->id,
                'points_type'           => $cmeDetails->coins_type == 'cash' ? 'cash' : 'point',
                'points'                => $cmeDetails->coins ?? 0,
                'point_credit_debit'    => 'credit',
            ]);
            return $memberPoint;
        }
        catch (Exception $e){
            throw new CustomErrorException($e);
        }
    }
}
