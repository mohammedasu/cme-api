<?php

namespace App\Services;

use App\Models\Member;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Log;

class FlyyService
{
    public function callFlyyApi($path, $post_data)
    {
        $partner_id     = config('constants.flyy_partner_id');
        $partner_key    = config('constants.flyy_partner_key');
        $flyy_url       = config('constants.flyy_base_url');
        $final_path     = $flyy_url . $partner_id . $path;
        $ch             = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            "PARTNER-KEY:$partner_key"
        ));
        curl_setopt_array($ch, array(
            CURLOPT_URL => $final_path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($post_data)
        ));
        $output = curl_exec($ch);
        return $output;
    }

    public function sendEventToFlyy($member_ref_no, $event_name)
    {
        Log::info('sendEvent to Flyy', ['member_ref_no'=>$member_ref_no, 'event_name'=> $event_name ]);
        $member_ref_no = $member_ref_no ?? null;
        $event_name = $event_name ?? null;
        if ($member_ref_no && $event_name) {
            $member_data = Member::where('member_ref_no', $member_ref_no)->first();
            if (!$member_data) {
                return ApiResponse::failureResponse('Member not found', null, 500);
            }
            $post_data = array("ext_user_id" => $member_ref_no, 'event_key' => $event_name);
            $response_data =  self::callFlyyApi('/user_event', $post_data);
            return $response_data;
        } else {
            return ApiResponse::failureResponse('Please send member ref no and event name', null, 422);
        }
    }
}