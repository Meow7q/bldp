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
            ->select(['id', 'user_id', 'year', 'month', 'file_url', 'title', 'audit_status', 'import_status','comment','auditor', 'auditor_id', 'created_at']);
    }

    public function updateStatus($user, $id, $status, $comment){
       return FileBldp::where('id', $id)
            ->update([
                'audit_status' => $status,
                'comment' => $comment,
                'auditor' => $user->nickname,
                'auditor_id' => $user->staffcode,
            ]);
    }
}
