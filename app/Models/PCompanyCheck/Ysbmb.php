<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class Ysbmb extends Model
{
    protected $table  = 't_ysbmb';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function getDeAttribute($value){
        return round($value*100, 2).'%';
    }

    public function getDfAttribute($value){
        return round($value*100, 2).'%';
    }
}
