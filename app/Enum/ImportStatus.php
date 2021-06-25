<?php


namespace App\Enum;


final class ImportStatus
{
    //待解析导入
    const STATUS_WAITING = 0;

    //解析导入成功
    const STATUS_SUCCESS = 1;

    //解析导入失败
    const STATUS_FAIL = 2;
}
