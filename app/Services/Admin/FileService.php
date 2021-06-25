<?php


namespace App\Services\Admin;


use App\Models\ImportData\FileBldp;
use ZipStream\File;

class FileService
{
    public function addToDb($data)
    {
        return FileBldp::create($data);
    }

    public function fileList($year = 0)
    {
        return FileBldp::when($year ?: false, function ($query) use($year){
            $query->where('year', $year);
        })
            ->select(['id', 'user_id', 'year', 'month', 'file_url', 'title', 'audit_status', 'import_status', 'created_at']);
    }

    public function updateStatus($id, $status){
       return FileBldp::where('id', $id)
            ->update([
                'audit_status' => $status
            ]);
    }
}
