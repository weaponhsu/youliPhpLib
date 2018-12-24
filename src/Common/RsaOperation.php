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
 * Time: 12:26 PM
 */

namespace youliPhpLib\Common;


use Exception;
use youliPhpLib\ErrMsg\Common\RsaOperationErrMsg;

class RsaOperation
{
    public static $instance = null;
    protected $_private_key_dir = '';
    protected $_public_key_dir = '';

    public function __construct($public_pem = '', $private_pem = '')
    {
        $this->_public_key_dir = !empty($public_pem) ? trim($public_pem) : '';
        $this->_private_key_dir = !empty($private_pem) ? trim($private_pem) : '';
        if(empty($this->_public_key_dir) || empty($this->_private_key_dir))
            throw new Exception(str_replace('%s', '密钥对', RsaOperationErrMsg::PEM_IS_EMPTY),
                RsaOperationErrMsg::PEM_IS_EMPTY_NO);
    }

    static public function getInstance($public_pem = '', $private_pem = ''){
        if(is_null(self::$instance))
            self::$instance = new self($public_pem, $private_pem);

        return self::$instance;
    }

    public function __clone(){
        return new Exception(
            str_replace('%s', 'rsaOperation', RsaOperationErrMsg::INSTANCE_NOT_ALLOW_TO_CLONE),
            RsaOperationErrMsg::INSTANCE_NOT_ALLOW_TO_CLONE_NO
        );
    }

    /**
     * 设置私钥
     * @param string $private_pem
     * @return $this
     * @throws Exception
     */
    public function setPrivateKey($private_pem = '') {
        if (empty($private_pem))
            throw new Exception(str_replace('%s', '私钥', RsaOperationErrMsg::PEM_IS_EMPTY),
                RsaOperationErrMsg::PEM_IS_EMPTY_NO);
        if (file_exists($private_pem))
            throw new Exception(str_replace('%s', '私钥', RsaOperationErrMsg::PEM_IS_NOT_EXISTS),
                RsaOperationErrMsg::PEM_IS_NOT_EXISTS_NO);

        $this->_private_key_dir = $private_pem;

        return $this;
    }

    /**
     * 设置公钥
     * @param string $public_pem
     * @return $this
     * @throws Exception
     */
    public function setPublicKey($public_pem = '') {
        if (empty($public_pem))
            throw new Exception(str_replace('%s', '公钥', RsaOperationErrMsg::PEM_IS_EMPTY),
                RsaOperationErrMsg::PEM_IS_EMPTY_NO);
        if (file_exists($public_pem))
            throw new Exception(str_replace('%s', '公钥', RsaOperationErrMsg::PEM_IS_NOT_EXISTS),
                RsaOperationErrMsg::PEM_IS_NOT_EXISTS_NO);

        $this->_public_key_dir = $public_pem;

        return $this;
    }

    /**
     * 读取私钥
     * @param string $pass
     * @return bool|resource
     * @throws Exception
     */
    protected function getPrivateKey($pass = ''){
        $_private_key_dir = file_get_contents($this->_private_key_dir);
        //这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        $pi_key = openssl_pkey_get_private($_private_key_dir, $pass);
        if (! $pi_key)
            throw new Exception(str_replace('%s', '私钥', RsaOperationErrMsg::PEM_IS_INVALID),
                RsaOperationErrMsg::PEM_IS_INVALID_NO);
        return $pi_key;
    }

    /**
     * 读取公钥
     * @return resource
     * @throws Exception
     */
    protected function getPublicKey(){
        $public_key_dir = file_get_contents($this->_public_key_dir);
        //判断公钥是否是可用的
        $pu_key = openssl_pkey_get_public($public_key_dir);
        if (! $pu_key)
            throw new Exception(str_replace('%s', '公钥', RsaOperationErrMsg::PEM_IS_INVALID),
                RsaOperationErrMsg::PEM_IS_INVALID_NO);
        return $pu_key;
    }


    /**
     * 私钥加密
     * @param $data 需要加密的数据
     * @return bool|string
     * @throws Exception
     */
    public function privateEncrypt($data){
        if(empty($data)) return false;

        if(is_array($data))
            $str = http_build_query($data);
        else
            $str = $data;

        try{
            $encrypted = $return = $encrypt_str = "";
            for ($i = 0; $i < 10000;) {
                if ($i <= strlen($str)) {
                    $step = 500;
                    $encrypt_str = substr($str, $i, $step);
                    openssl_private_encrypt($encrypt_str, $encrypted, $this->getPrivateKey());
                    $return .= base64_encode($encrypted);
                } else {
                    break;
                }
                $i = $i + $step;
            }
            return $return;
        }catch(Exception $e){
            throw new Exception(str_replace('%s', '私钥', RsaOperationErrMsg::ENCRYPT_FAILURE),
                RsaOperationErrMsg::ENCRYPT_FAILURE_NO);
        }
    }

    /**
     * 公钥解密
     * @param $res 需要解密的内容
     * @return bool
     * @throws Exception
     */
    public function publicDecrypt($res){
        if(empty($res) || !is_string($res)) return false;

        try{
            $res_arr = explode('=', $res);
            $return = '';
            foreach ($res_arr as $val) {
                if (! empty($val)) {
                    $decrypted = '';
                    openssl_public_decrypt(base64_decode($val . '='), $decrypted, $this->getPublicKey());
                    $return .= $decrypted;
                }
            }
            return $return;
        }catch(Exception $e){
            throw new Exception(str_replace('%s', '公钥', RsaOperationErrMsg::DECRYPT_FAILURE),
                RsaOperationErrMsg::DECRYPT_FAILURE_NO);
        }
    }

    /**
     * 公钥加密
     * @param $data 需要加密的数据
     * @return bool|string
     * @throws Exception
     */
    public function publicEncrypt($data){
        if(empty($data)) return false;

        if(is_array($data))
            $str = http_build_query($data);
        else
            $str = $data;

        try{
            $encrypted = $return = $encrypt_str = "";
            for ($i = 0; $i < 10000;) {
                if ($i <= strlen($str)) {
                    $step = 500;
                    $encrypt_str = substr($str, $i, $step);
                    openssl_public_encrypt($encrypt_str, $encrypted, $this->getPublicKey());
                    $return .= base64_encode($encrypted);
                } else {
                    break;
                }
                $i = $i + $step;
            }
            return $return;
        }catch(Exception $e){
            throw new Exception(str_replace('%s', '公钥', RsaOperationErrMsg::ENCRYPT_FAILURE),
                RsaOperationErrMsg::ENCRYPT_FAILURE_NO);
        }
    }

    /**
     * @param $res// 需要解密的内容
     * @return bool
     * @throws Exception
     */
    public function privateDecrypt($res)
    {
        if (empty($res) || !is_string($res)) return false;

        try {
            $res_arr = explode('=', $res);
            $return = '';
            foreach ($res_arr as $val) {
                if (! empty($val)) {
                    $decrypted = '';
                    openssl_private_decrypt(base64_decode($val . '='), $decrypted, $this->getPrivateKey());
                    $return .= $decrypted;
                }
            }
            return $return;
        } catch (Exception $e) {
            throw new Exception(str_replace('%s', '私钥', RsaOperationErrMsg::DECRYPT_FAILURE),
                RsaOperationErrMsg::DECRYPT_FAILURE_NO);
        }

    }

    /**
     * RSA 私钥签名
     * @param $data 签名数据
     * @param string $pass 密码
     * @return bool|string
     * @throws Exception
     */
    public function sign($data, $pass = '') {
        try {
            if (is_array($data))
                $data = urldecode(http_build_query($data));

            $private_key = $this->getPrivateKey($pass);

            $original_str = urldecode($data);

            openssl_sign($original_str, $sign, $private_key);
            openssl_free_key($private_key);

            return base64_encode($sign);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * RSA 公钥验签
     * @param $sign
     * @param $original_str
     * @return bool
     * @throws Exception
     */
    public function verify($sign, $original_str) {
        try {

            $this->getPublicKey();
            $public_content=file_get_contents($this->_public_key_dir);

            $public_key=openssl_get_publickey($public_content);

            $sign=base64_decode($sign);//得到的签名

            $result=(bool)openssl_verify($original_str, $sign, $public_key);

            openssl_free_key($public_key);

            return $result;

        } catch (Exception $e) {
            throw $e;
        }

    }

}