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
 * Time: 11:40 AM
 */

namespace youliPhpLib\Common;


use Exception;
use youliPhpLib\ErrMsg\Common\StringErrMsg;

class StringOperation
{
    /**
     * emoji 2 json string
     * @param null $nickname
     * @return mixed|string
     */
    static public function emojiTextEncode($nickname = null)
    {
        $str = is_string($nickname) && $nickname !== null ? trim($nickname) : '';
        if (empty($str)) return $nickname == null ? '' : $str;

        //暴露出unicode
        $text = json_encode($str);

        //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
        $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
            return addslashes($str[0]);
        }, $text);

        return json_decode($text);
    }

    /**
     * json string 2 emoji
     * @param null $decode_nickname
     * @return mixed
     */
    static public function emojiTextDecode($decode_nickname = null)
    {
        //暴露出unicode
        $text = json_encode($decode_nickname);

        //将两条斜杠变成一条，其他不动
        $text = preg_replace_callback('/\\\\\\\\/i', function ($str) {
            return '\\';
        }, $text);
        return json_decode($text);
    }

    /**
     * 检测是否是json字符串
     * @param string $str 带检测字符串
     * @return bool $str是json字符串返回false 反之返回true
     */
    static public function isJsonStr($str = ''){
        return is_null(json_decode($str));
    }

    /**
     * @param $data
     * @return bool
     */
    static public function isSerialized($data){
        $data = trim($data);
        if ('N;' == $data)
            return true;
        if (!preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }
        return false;
    }

    /**
     * 生成随机字符串
     * @param string $type
     * @param int $length
     * @return string
     */
    static public function getRandStr($type = 'num', $length = 6){
        $type = !empty($type) && in_array($type, ['num', 'letter', 'mix']) ? $type : 'num';
        $length = !empty($length) && is_numeric($length) ? $length : 6;

        $str = null;
        if($type == 'mix')
            $type = 'abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*(){}[].,<>\'":';
        else if($type == 'letter')
            $type = 'abcdefghijklmnopqrstuvwxyz';
        else if($type == 'num')
            $type = '0123456789';
        $max = strlen($type)-1;

        $return = '';
        for($i=0; $i<$length; $i++){
            $return .= $type[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $return;
    }

    /**
     * 生成订单编号
     * @return string
     */
    static public function genOrderSn(){
        $order_id_main = date('YmdHis') . substr(ip2long(self::getIP()), -5) . mt_rand(100, 999);

        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;

        for($i=0; $i<$order_id_len; $i++){
            $order_id_sum += (int)(substr($order_id_main,$i,1));
        }

        $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        return $order_id;
    }

    static public function mobileIsValid($mobile = '')
    {
        $mobile = !empty($mobile) && is_string($mobile) ? trim($mobile) : '';
        if (empty($mobile))
            throw new Exception("参数不能为空", "001");

        //判断手机号码长度
        if (strlen($mobile) < 11)
            throw new Exception(StringErrMsg::MOBILE_LENGTH_INVALID, StringErrMsg::MOBILE_LENGTH_INVALID_NO);
//            throw new Exception("请输入正确手机号码", "002");

        //正则手机号码是否有效
        preg_match_all("/^13[0-9]{1}[0-9]{8}$|147[0-9]{8}|15[0-9]{1}[0-9]{8}$|18[0-9]{9}|17[0-9]{1}[0-9]{8}$/", $mobile, $arr);
        if(empty($arr[0][0]))
            throw new Exception(StringErrMsg::MOBILE_INVALID, StringErrMsg::MOBILE_INVALID_NO);

        return true;
    }

    /**
     * 校验邮箱有效性
     * @param string $email
     * @return bool
     * @throws Exception
     */
    static public function emailIsValid($email = ''){
        $email = !empty($email) && is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ?
            trim($email) : '';

        if (empty($email))
            throw new Exception(StringErrMsg::EMAIL_INVALID, StringErrMsg::EMAIL_INVALID_NO);

        $res = checkdnsrr(array_pop(explode("@", $email)), "MX");

        if ($res === false)
            throw new Exception(StringErrMsg::EMAIL_ADDRESS_INVALID, StringErrMsg::EMAIL_ADDRESS_INVALID_NO);

        return true;
    }

    /**
     * 验证身份证号码
     * @param $identification_num
     * @return bool
     * @throws Exception
     */
    static public function identificationNumIsValid($identification_num){
        $identification_num = ! empty($identification_num) && is_string($identification_num) &&
        strlen(trim($identification_num)) ? trim($identification_num) : '';

        if (empty($identification_num) || strlen($identification_num) < 18)
            throw new Exception(StringErrMsg::IDENTIFICATION_NUM_LENGTH_INVALID,
                StringErrMsg::IDENTIFICATION_NUM_LENGTH_INVALID_NO);

        $city_code_arr = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );

        if (!preg_match('/^[\d]{17}[xX\d]$/', $identification_num))
            throw new Exception(StringErrMsg::IDENTIFICATION_NUM_FORMATTER_INVALID,
                StringErrMsg::IDENTIFICATION_NUM_FORMATTER_INVALID_NO);

        if (!in_array(substr($identification_num, 0, 2), $city_code_arr))
            throw new Exception(StringErrMsg::IDENTIFICATION_NUM_FORMATTER_INVALID_1,
                StringErrMsg::IDENTIFICATION_NUM_FORMATTER_INVALID_1_NO);

        $identification_num = preg_replace('/[xX]$/i', 'a', $identification_num);
        $identification_num_length = strlen($identification_num);
        if ($identification_num_length == 18) {
            $val_birthday = substr($identification_num, 6, 4) . '-' . substr($identification_num, 10, 2) . '-' . substr($identification_num, 12, 2);
        } else {
            $val_birthday = '19' . substr($identification_num, 6, 2) . '-' . substr($identification_num, 8, 2) . '-' . substr($identification_num, 10, 2);
        }
        if (date('Y-m-d', strtotime($val_birthday)) != $val_birthday)
            throw new Exception(StringErrMsg::IDENTIFICATION_NUM_INVALID, StringErrMsg::IDENTIFICATION_NUM_INVALID_NO);

        return true;
    }

    /**
     * 获取IP
     * @return array|false|null|string
     */
    static public function getIP()
    {
        $real_ip = null;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $real_ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $real_ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $real_ip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $real_ip = getenv("HTTP_CLIENT_IP");
            } else {
                $real_ip = getenv("REMOTE_ADDR");
            }
        }
        return $real_ip ? $real_ip : '';
    }
}