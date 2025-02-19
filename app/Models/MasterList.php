<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class MasterList extends Model
{
    use HasFactory;

    protected $table = 'tbl_voterslist';

    
    public function coordinators()
    {
        return $this->hasMany(Coordinator::class, 'voters_id', 'id');
    }

    public function coordinator()
{
    return $this->hasOne(Coordinator::class, 'coordinator_id', 'id');
}

public function purokLeader()
{
    return $this->hasOne(PurokLeader::class, 'purok_leader_id', 'id');
}

public function householdLeader()
{
    return $this->hasOne(HouseholdLeader::class, 'household_leader_id', 'id');
}

public function householdMember()
    {
        return $this->hasOne(HouseholdMember::class, 'household_member_id', 'id');
    }



}


