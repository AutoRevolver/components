<?php

namespace revolver\components\validators;

use yii\validators\RegularExpressionValidator;

/**
 * 手机号验证器
 */
class MobileValidator extends RegularExpressionValidator
{
    /**
     * @var string 手机号正则
     */
    public $pattern = '/^((\+86)|(86))?(1(3[0-9]|4[57]|5[0-35-9]|7\d|8[0-9]))\d{8}$/';

    /**
     * @var string 错误消息
     */
    public $message = '请输入正确的手机号。';
}
