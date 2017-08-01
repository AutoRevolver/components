<?php

namespace revolver\components\validators;

use yii\validators\Validator;

/**
 * 身份证验证器
 */
class IdcardValidator extends Validator
{
    /**
     * @var string 错误消息
     */
    public $message = '请输入正确的身份证号。';

    /**
     * @var array 省code
     */
    protected static $provinceAndCitys = [
        11 => '北京',
        12 => '天津',
        13 => '河北',
        14 => '山西',
        15 => '内蒙古',
        21 => '辽宁',
        22 => '吉林',
        23 => '黑龙江',
        31 => '上海',
        32 => '江苏',
        33 => '浙江',
        34 => '安徽',
        35 => '福建',
        36 => '江西',
        37 => '山东',
        41 => '河南',
        42 => '湖北',
        43 => '湖南',
        44 => '广东',
        45 => '广西',
        46 => '海南',
        50 => '重庆',
        51 => '四川',
        52 => '贵州',
        53 => '云南',
        54 => '西藏',
        61 => '陕西',
        62 => '甘肃',
        63 => '青海',
        64 => '宁夏',
        65 => '新疆',
        71 => '台湾',
        81 => '香港',
        82 => '澳门',
        91 => '国外',
    ];

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $valid = !is_array($value) &&
            self::checkAddressCode($value) &&
            self::checkBirthday($value) &&
            (self::isNewIdcard($value) || self::isOldIdcard($value));

        return $valid ? null : [$this->message, []];
    }

    /**
     * 校验地址码
     *
     * @param $idcard
     * @return bool
     */
    protected static function checkAddressCode($idcard)
    {
        if (preg_match('/^[1-9]\d{5}/', $idcard)) {
            if (isset(self::$provinceAndCitys[substr($idcard, 0, 2)])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 校验生日
     *
     * @param $idcard
     * @return bool
     */
    protected static function checkBirthday($idcard)
    {
        if (strlen($idcard) == 15) {
            $birthday = '19' . substr($idcard, 6, 6);
        } elseif (strlen($idcard) == 18) {
            $birthday = substr($idcard, 6, 8);
        }

        if (isset($birthday)) {
            list($year, $month, $day) = sscanf($birthday, '%4d%2d%2d');

            return strtotime($year . '-' . $month . '-' . $day) !== false;
        }

        return false;
    }

    /**
     * 是否旧版身份证
     *
     * @param $idcard
     * @return int
     */
    protected static function isOldIdcard($idcard)
    {
        return preg_match('/^\d{15}$/', $idcard);
    }

    /**
     * 是否 18 位身份证
     *
     * @param $idcard
     * @return bool
     */
    protected static function isNewIdcard($idcard)
    {
        $arrExp = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2]; // 加权因子
        $arrValid = [1, 0, "X", 9, 8, 7, 6, 5, 4, 3, 2]; // 校验码

        if (preg_match('/^\d{17}(\d|x)$/i', $idcard)) {
            $sum = 0;

            for ($i = 0; $i < 17; $i++) {
                // 对前17位数字与权值乘积求和
                $sum += intval(substr($idcard, $i, 1)) * $arrExp[$i];
            }

            // 计算模（固定算法）
            $idx = $sum % 11;

            // 检验第18为是否与校验码相等
            return $arrValid[$idx] == strtoupper($idcard[17]);
        } else {
            return false;
        }
    }
}
