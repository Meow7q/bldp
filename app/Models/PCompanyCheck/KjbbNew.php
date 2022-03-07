<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class KjbbNew extends Model
{
    protected $table  = 't_kjbb_new';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected function getKgTbAttribute($value){
        return (round($value, 4)*100).'%';
    }

    protected function getCtTbAttribute($value){
        return (round($value, 4)*100).'%';
    }
}
