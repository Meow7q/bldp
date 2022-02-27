<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class Ysjlcb extends Model
{
    protected $table  = 't_ysjlcb';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function getDeAttribute($value){
        return round($value, 2).'%';
    }

    public function getDfAttribute($value){
        return round($value, 2).'%';
    }
}
