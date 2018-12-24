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
 * Time: 3:14 PM
 */

namespace youliPhpLib\Common;


use Exception;
use youliPhpLib\ErrMsg\Common\EncryptErrMsg;

class Encrypt
{
    static protected $_access_key = '';
    static protected $_data = [];
    static protected $_sign;

    static public function setAccessKey($access_key = ''){
        self::$_access_key = $access_key;
    }

    /**
     * MD5加密
     * @param array $data
     * @return Exception
     */
    static public function Md5Encrypt($data = []){
        $data = !empty($data) && is_array($data) ? $data : '';
        if(empty(self::$_access_key)){
            return new Exception(EncryptErrMsg::ACCESS_KEY_IS_NULL, EncryptErrMsg::ACCESS_KEY_IS_NULL_NO);
        }
        if(empty($data)){
            return new Exception(EncryptErrMsg::DATA_IS_NULL, EncryptErrMsg::DATA_IS_NULL_NO);
        }

        foreach($data as $index => $value){
            if($index == 'sign' || $index == 'uuid' || strpos($index, '/') !== false || (empty($value) && $value != 0)){
                unset($data[$index]);
            }
        }
        ksort($data);

        if(empty($data)){
            return new Exception(EncryptErrMsg::DATA_IS_NOT_NULL, EncryptErrMsg::DATA_IS_NOT_NULL_NO);
        }

        return md5(urldecode(http_build_query($data)) . self::$_access_key);

    }

    /**
     * MD5解密
     * @param string $sign
     * @param array $data
     * @return bool|Exception
     */
    static public function Md5Decrypt($sign = '', $data = []){
        $sign = !empty($sign) && is_string($sign) && strlen($sign) === 32 ? $sign : '';
        if(empty($sign)){
            return new Exception(EncryptErrMsg::SIGN_IS_NOT_NULL, EncryptErrMsg::SIGN_IS_NOT_NULL_NO);
        }

        $real_sign = self::Md5Encrypt($data);

        if($real_sign !== $sign){
            return new Exception(EncryptErrMsg::VERIFICATION_ERROR . 'real_sign' . $real_sign,
                EncryptErrMsg::VERIFICATION_ERROR_NO);
        }

        return true;
    }

}