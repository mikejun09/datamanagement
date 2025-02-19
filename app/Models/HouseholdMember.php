<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdMember extends Model
{
    use HasFactory;

    protected $table = 'tbl_brgy_household_member';

    protected $fillable = ['household_leader_id', 'household_member_id', 'remarks'];

    // A Household Member belongs to one Household Leader
    public function householdLeader()
    {
        return $this->belongsTo(HouseholdLeader::class, 'household_leader_id', 'household_leader_id');
    }

    public function voter()
    {
        return $this->belongsTo(MasterList::class, 'household_member_id', 'id');
    }
    
}
