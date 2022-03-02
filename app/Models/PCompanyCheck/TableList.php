<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class TableList extends Model
{
    protected $table  = 'p_check_table_list';

    protected $guarded = [];

    protected $hidden = ['updated_at'];

//    public function getFilePathAttribute($value){
//        return '/upload/'.$value;
//    }
}
