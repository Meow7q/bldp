<?php


namespace App\Models\PCompanyCheck;


use Illuminate\Database\Eloquent\Model;

class TableList extends Model
{
    protected $table  = 'p_check_table_list';

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}
