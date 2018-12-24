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
 * Time: 2:56 PM
 */

namespace youliPhpLib\ErrMsg\Common;


class RequestErrMsg
{
    const SL_CERT_PATH_IS_EMPTY = 'cert证书路径不能为空';
    const SL_CERT_PATH_IS_EMPTY_NO = '300001';

    const SSL_KEY_PEM_IS_EMPTY = 'pem证书路径不能为空';
    const SSL_KEY_PEM_IS_EMPTY_NO = '300002';

    const API_FAILURE = '接口调用失败';
    const API_FAILURE_NO = '300003';

}