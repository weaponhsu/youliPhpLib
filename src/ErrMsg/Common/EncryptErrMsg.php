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
 * Time: 3:15 PM
 */

namespace youliPhpLib\ErrMsg\Common;


class EncryptErrMsg
{
    const ACCESS_KEY_IS_NULL = 'access_key不能为空';
    const ACCESS_KEY_IS_NULL_NO = '400001';

    const DATA_IS_NULL = '加密参数必须为数组';
    const DATA_IS_NULL_NO = '400002';

    const DATA_IS_NOT_NULL = '加密参数为空';
    const DATA_IS_NOT_NULL_NO = '400003';

    const SIGN_IS_NOT_NULL = '解密参数不能为空';
    const SIGN_IS_NOT_NULL_NO = '400004';

    const VERIFICATION_ERROR = '解密失败';
    const VERIFICATION_ERROR_NO = '400005';
}