<?php

namespace App\Services;

use App;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Repositories\MemberRepository;
use App\Exceptions\CustomErrorException;

class MemberService
{
    public function __construct()
    {
        $this->repository = new MemberRepository();
    }

    /**
     * Function to get member details
    */

    public function findByMultipleFields($request)
    {
        Log::info('MemberService | findByMultipleFields', $request->all());
        try {
            $where = [];
            if($request->has('member_id')) {
                $where[] = ['id','=',$request->member_id];
            }
            return $this->repository->findByMultipleFields($where,false, $request);
        } catch (Exception $e){
            throw new CustomErrorException($e);
        }
    }


}
