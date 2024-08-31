<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelecallerMemberEngagementHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'telecaller_member_id',
        'telecaller_id',
        'channel_type',
        'channel_id',
        "content_id",
        "content_type",
        "is_read"
    ];

    public function telecallerMember(){
        return $this->belongsTo(TelecallerMembers::class,'telecaller_member_id','id');
    }
}
