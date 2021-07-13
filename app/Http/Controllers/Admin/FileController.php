<?php


namespace App\Http\Controllers\Admin;


use App\Enum\AdminRoleType;
use App\Enum\AuditStatus;
use App\Enum\ImportStatus;
use App\Http\Controllers\Controller;
use App\Services\Admin\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $file_service;

    public function __construct(FileService  $file_service)
    {
        $this->file_service = $file_service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(Request $request){
        $validated = $this->validate($request, ['year'=>'required', 'month'=>'required', 'title'=>'required', 'file_url'=>'required']);
        if($request->user->permission != AdminRoleType::ROLE_IMPORT){
            return $this->fail('权限不足，无法导入!');
        }
        $rs = $this->file_service->addToDb(array_merge($validated, [
            'user_id' => $request->user->id,
            'audit_status' => AuditStatus::STATUS_WAITING,
            'import_status' => ImportStatus::STATUS_WAITING
        ]));
        return $this->success([
            'id' => $rs->id
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function fileList(Request $request){
        $validated = $this->validate($request, ['keyword' => '']);
        $query = $this->file_service->fileList($validated['keyword']??0);
        $list = $query->paginate(\request('per_page'), 15)->toArray();
        return $this->success($list);
    }

    public function updateAuditStatus(Request $request){
        $user = $request->user;
        $validated = $this->validate($request, ['id'=>'required', 'audit_status'=>'required|in:1,2',  'comment'=>'']);
        if($request->user->permission != AdminRoleType::ROLE_AUDIT){
            return $this->fail('权限不足!');
        }
        $rs = $this->file_service->updateStatus($user, $validated['id'], $validated['audit_status'], $validated['comment']??'');
        return $this->message('操作成功!');
    }
}
