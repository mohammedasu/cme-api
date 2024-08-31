<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelecallerMembers extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_number',
        'whatsapp_number',
        'first_name',
        'last_name',
        'email',
        'city',
        'speciality',
        'state',
        'reg_no',
        'reg_state',
        'year_of_graduation',
        'primary_status',
        'secondary_status',
        'notes',
        'active_status',
        "universal_member_ref_no"
    ];

    public function telecaller(){
        return $this->hasOneThrough(Telecallers::class,TelecallerMemberMap::class,'telecaller_member_id','id','id','telecaller_id');
    }

}
