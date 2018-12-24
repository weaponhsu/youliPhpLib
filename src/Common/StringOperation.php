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

}