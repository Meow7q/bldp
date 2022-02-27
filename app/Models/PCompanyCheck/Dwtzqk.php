<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class Dwtzqk extends Model
{
    protected $table  = 't_dwtzqk';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'id'];

    public function getTzhblAttribute($value){
        return round($value*100, 2).'%';
    }
}
