<?php


namespace App\Enum;


class UserPermission
{
    //管理员，可以编辑
    const ADMIN = 1;

    //游客，仅能查看
    const VISITOR = 2;
}
