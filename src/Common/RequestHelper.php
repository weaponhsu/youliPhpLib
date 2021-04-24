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
 * Time: 2:52 PM
 */

namespace youliPhpLib\Common;


use Exception;
use youliPhpLib\ErrMsg\Common\RequestErrMsg;

class RequestHelper
{
    static public $sl_cert_path = '';
    static public $ssl_key_pem = '';

    /**
     * @param $sl_cert_path
     * @return $this
     */
    static public function setSlCertPath($sl_cert_path)
    {
        self::$sl_cert_path = $sl_cert_path;
    }

    /**
     * @param $ssl_key_pem
     * @return $this
     */
    static public function setSslKeyPem($ssl_key_pem)
    {
        self::$ssl_key_pem = $ssl_key_pem;
    }

    static public function curlRequest($url, $data = [], $method = 'GET', $headers = [], $use_cert = false, $second = 30, $return_resp_code = False)
    {
        $ch = curl_init();
        //设置超时与地址
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);

        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格校验
        }

        //设置header
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        }else{
            if ($return_resp_code === true)
                curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($use_cert === true) {
            //设置证书
            if (empty(self::$sl_cert_path))
                throw new Exception(RequestErrMsg::SL_CERT_PATH_IS_EMPTY, RequestErrMsg::SL_CERT_PATH_IS_EMPTY_NO);
            else{
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLCERT, self::$sl_cert_path);
            }

            if (empty(self::$ssl_key_pem))
                throw new Exception(RequestErrMsg::SSL_KEY_PEM_IS_EMPTY, RequestErrMsg::SSL_KEY_PEM_IS_EMPTY_NO);
            else {
                curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($ch, CURLOPT_SSLKEY, self::$ssl_key_pem);
            }
        }

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_NOBODY, false);
        $document = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $resp_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($return_resp_code === true) {
            $resp_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $resp_headers = curl_getinfo($ch);
            return [$resp_code, $resp_headers];
        }

        if(curl_errno($ch)){
            throw new Exception(RequestErrMsg::API_FAILURE . curl_error($ch), '999');
        }
        curl_close($ch);
//        return $document;
        return [$header_size, $document];
    }

}
