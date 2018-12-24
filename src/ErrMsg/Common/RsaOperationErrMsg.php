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
 * Time: 12:28 PM
 */

namespace youliPhpLib\ErrMsg\Common;


class RsaOperationErrMsg
{
    const INSTANCE_NOT_ALLOW_TO_CLONE = '%s禁止clone';
    const INSTANCE_NOT_ALLOW_TO_CLONE_NO = '100001';

    const PEM_IS_EMPTY = '%s文件名不能为空';
    const PEM_IS_EMPTY_NO = '100002';

    const PEM_IS_NOT_EXISTS = '%s文件不存在';
    const PEM_IS_NOT_EXISTS_NO = '100003';

    const PEM_IS_INVALID = '无效%s文件';
    const PEM_IS_INVALID_NO = '100004';

    const ENCRYPT_FAILURE = '%s加密失败';
    const ENCRYPT_FAILURE_NO = '100005';

    const DECRYPT_FAILURE = '%s解密失败';
    const DECRYPT_FAILURE_NO = '100006';
}