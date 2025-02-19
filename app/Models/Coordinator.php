<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Coordinator extends Model
{
    use HasFactory;

    protected $table = 'tbl_brgy_coordinator';

    protected $fillable = ['coordinator_id', 'remarks'];

    // A Coordinator belongs to one voter
    public function voter()
    {
        return $this->belongsTo(MasterList::class, 'coordinator_id', 'id');
    }

    // A Coordinator has many Purok Leaders
    public function purokLeaders()
    {
        return $this->hasMany(PurokLeader::class, 'coordinator_id', 'coordinator_id');
    }
}