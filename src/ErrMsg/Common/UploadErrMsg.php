<?php
/**
 *
 *            ┏┓　　  ┏┓+ +
 *           ┏┛┻━━━━━┛┻┓ + +
 *           ┃         ┃ 　
 *           ┃   ━     ┃ ++ + + +
 *          ████━████  ┃+
 *           ┃　　　　　 ┃ +
 *           ┃　　　┻　　┃
 *           ┃　　　　　 ┃ + +
 *           ┗━┓　　　┏━┛
 *             ┃　　　┃　　　　
 *             ┃　　　┃ + + + +
 *             ┃　　　┃    Code is far away from bug with the alpaca protecting
 *             ┃　　　┃ + 　　　　        神兽保佑,代码无bug
 *             ┃　　　┃
 *             ┃　　　┃　　+
 *             ┃     ┗━━━┓ + +
 *             ┃         ┣┓
 *             ┃ 　　　　　┏┛
 *             ┗┓┓┏━━┳┓┏━┛ + + + +
 *              ┃┫┫  ┃┫┫
 *              ┗┻┛  ┗┻┛+ + + +
 * Created by PhpStorm.
 * User: weaponhsu
 * Date: 2018/12/24
 * Time: 2:36 PM
 */

namespace youliPhpLib\ErrMsg\Common;


class UploadErrMsg
{
    const UPLOAD_NO_FILE = '请选择文件';
    const UPLOAD_NO_FILE_NO = '200001';

    const UPLOAD_INVALID_FILE_TYPE = '%s是非法上传类型';
    const UPLOAD_INVALID_FILE_TYPE_NO = '200002';

    const UPLOAD_INVALID_FILE_MAX_SIZE = '%s超过限定文件大小%max_size';
    const UPLOAD_INVALID_FILE_MAZ_SIZE_NO = '200003';

    const UPLOAD_INVALID_FILE_MAX_WIDTH = '%s超过限定文件宽度%w';
    const UPLOAD_INVALID_FILE_MAX_WIDTH_NO = '200004';

    const UPLOAD_INVALID_FILE_MAX_HEIGHT = '%s超过限定文件高度%h';
    const UPLOAD_INVALID_FILE_MAX_HEIGHT_NO = '200005';

    const UPLOAD_INVALID_DESTINATION_DIR = '%s目录不存在';
    const UPLOAD_INVALID_DESTINATION_DIR_NO = '200006';

    const UPLOAD_FILE_MOVE_FAILED = '%s文件移动失败';
    const UPLOAD_FILE_MOVE_FAILED_NO = '200007';

    const UPLOAD_FILE_CONVERT_2_WEBP_FALIURE = '%s文件转webp格式失败';
    const UPLOAD_FILE_CONVERT_2_WEBP_FAILURE_no = '200008';

    const UPLOAD_SUCCESS = '%s文件上传成功';
    const UPLOAD_SUCCESS_NO = '000';

    const UPLOAD_SUCCESS_DESCRIPTION = '%s文件上传成功，请点击按钮返回上个页面';
    const UPLOAD_SUCCESS_DESCRIPTION_NO = '200009';

    const UPLOAD_MAKE_DIR_FAILED = '%s保存路径创建失败';
    const UPLOAD_MARK_DIR_FAILED = '200010';
}