<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class PurokLeader extends Model
{
    use HasFactory;

    protected $table = 'tbl_brgy_purok_leader';

    protected $fillable = ['coordinator_id', 'purok_leader_id', 'remarks'];

    // A Purok Leader belongs to one Coordinator
    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class, 'coordinator_id', 'coordinator_id');
    }

    // A Purok Leader has many Household Leaders
    public function householdLeaders()
    {
        return $this->hasMany(HouseholdLeader::class, 'purok_leader_id', 'purok_leader_id'); 
    }

    // A Purok Leader belongs to a voter (MasterList)
    public function voter()
    {
        return $this->belongsTo(MasterList::class, 'purok_leader_id', 'id');
    }
}

