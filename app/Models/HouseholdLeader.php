<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdLeader extends Model
{
    use HasFactory;

    protected $table = 'tbl_brgy_household_leader';

    protected $fillable = ['purok_leader_id', 'household_leader_id', 'remarks'];

    // A Household Leader belongs to one Purok Leader
    public function purokLeader()
    {
        return $this->belongsTo(PurokLeader::class, 'purok_leader_id', 'id'); // Fixed third parameter
    }

    // A Household Leader has many Household Members
    public function householdMembers()
    {
        return $this->hasMany(HouseholdMember::class, 'household_leader_id', 'household_leader_id');
    }

    // Household Leader belongs to MasterList (voter)
    public function voter()
    {
        return $this->belongsTo(MasterList::class, 'household_leader_id', 'id');
    }
}

