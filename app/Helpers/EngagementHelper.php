<?php

namespace App\Helpers;

use App\Models\TelecallerMemberEngagementHistory;
use Illuminate\Support\Facades\Log;

class EngagementHelper
{
    public static function updateHistory($universMemberRefNo, $contentType, $contentId)
    {
        Log::info("EngagementHelper | updateHistory",["parameters ref_no" => $universMemberRefNo, "contentType" => $contentType, "contentId" => $contentId ]);
        if (!in_array($contentType, ["live_event", "cases", "videos", "news_letter", "survey"])) {
            return [
                "status"    => false,
                "msg"       => "Invalid content type",
            ];
        }
        $where = [
            ["content_id", $contentId],
            ["content_type", $contentType],
            ["is_read", 0]
        ];
        $result = TelecallerMemberEngagementHistory::whereHas('telecallerMember', function ($q) use ($universMemberRefNo) {
            return $q->where('universal_member_ref_no', $universMemberRefNo);
        })->where($where)->update([
            "is_read" => 1
        ]);
        Log::info($result);
        if ($result) {
            return [
                "status" => true,
                "msg" => "Data updated successfully",
            ];
        }
        return [
            "status" => false,
            "msg" => "No content found",
        ];
    }
}
