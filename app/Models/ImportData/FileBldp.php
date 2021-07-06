<?php

namespace App\Models\ImportData;

use Illuminate\Database\Eloquent\Model;

class FileBldp extends Model
{
    //
    protected $table = 'file_bldp';

    protected $guarded = [];

    public function getFileUrlAttribute($value){
        return env('APP_URL').'/'.$value;
    }
}
