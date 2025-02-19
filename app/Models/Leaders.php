<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaders extends Model
{
    use HasFactory;
    protected $table = 'tbl_leaders';
    protected $fillable = [
        'master_list_id'
    ];

    public function leader(){
        return $this->hasOne(MasterList::class,'id', 'master_list_id');
      }

      public function members()
    {
        return $this->hasMany(Member::class);
    }
}
