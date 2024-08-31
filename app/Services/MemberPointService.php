<?php

namespace App\Services;

use App;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\MemberPointRepository;
use App\Exceptions\CustomErrorException;

class MemberPointService
{
    public function __construct()
    {
        $this->repository               = new MemberPointRepository();
        $this->memberPointHistoryService = new MemberPointHistoryService();
    }

    /**
     * Function to store / update member points and details
    */

    public function createOrUpdate($cmeDetails,$memberId)
    {
        Log::info('MemberPointService | createOrUpdate', ['CME Details'=>$cmeDetails,'Member Id'=>$memberId]);
        try {
            $memberDetailsPoint = $this->memberPointHistoryService
                                        ->findByMultipleFields([
                                            'member_type'   => 'member', 
                                            'member_id'     => $memberId,
                                            'content_type'  => 'cme', 
                                            'content_id'    => $cmeDetails->id
                                        ]);
            if(!$memberDetailsPoint){
                $memberPoint = $this->repository->findByMultipleFields(['member_id' => $memberId, 'member_type' => 'member']);
                if ($memberPoint) {
                    $data = $this->createRequest($cmeDetails, $memberId, $memberPoint);
                    $memberPoint = $this->repository->update($data, $memberPoint);
                }else{
                    $data = $this->createRequest($cmeDetails, $memberId);
                    $memberPoint = $this->repository->store($data);
                }
                $this->memberPointHistoryService->store($cmeDetails,$memberId);
            }
        }
        catch (Exception $e){
            throw new CustomErrorException($e);
        }
    }

    public function createRequest($cmeDetails,$memberId,$memberPoint=null)
    {
        $totalPoints = $redeemPoints = $availablePoints = 0;
        $totalCash   = $redeemCash   = $availableCash   = 0;
        if($memberPoint){
            if($cmeDetails->coins_type == 'coin'){
                $totalPoints    = $memberPoint->total_points + $cmeDetails->coins ?? 0;
                $redeemPoints   = $memberPoint->redeem_points;
                $availablePoints= $memberPoint->available_points + $cmeDetails->coins ?? 0;

                $totalCash      = $memberPoint->total_cash;
                $redeemCash     = $memberPoint->redeem_cash;
                $availableCash  = $memberPoint->available_cash;
            }else{
                $totalPoints    = $memberPoint->total_points;
                $redeemPoints   = $memberPoint->redeem_points;
                $availablePoints= $memberPoint->available_points;

                $totalCash      = $memberPoint->total_cash + $cmeDetails->coins ?? 0;
                $redeemCash     = $memberPoint->redeem_cash;
                $availableCash  = $memberPoint->total_cash + $cmeDetails->coins ?? 0;
            }

            return [
                'total_points'      => $totalPoints,
                'redeem_points'     => $redeemPoints,
                'available_points'  => $availablePoints,
                'total_cash'        => $totalCash,
                'redeem_cash'       => $redeemCash,
                'available_cash'    => $availableCash,
            ];

        }
        else{
            if($cmeDetails->coins_type == 'coin'){
                $totalPoints    = $cmeDetails->coins ?? 0;
                $availablePoints= $cmeDetails->coins ?? 0;
            }else{
                $totalCash      = $cmeDetails->coins ?? 0;
                $availableCash  = $cmeDetails->coins ?? 0;
            }
            return [
                'member_id'         => $memberId,
                'member_type'       => 'member',
                'total_points'      => $totalPoints,
                'redeem_points'     => $redeemPoints,
                'available_points'  => $availablePoints,
                'total_cash'        => $totalCash,
                'redeem_cash'       => $redeemCash,
                'available_cash'    => $availableCash,
            ];
        }
    }
}
