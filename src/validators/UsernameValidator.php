<?php

namespace revolver\components\validators;

use yii\validators\RegularExpressionValidator;

/**
 * 用户名验证器
 */
class UsernameValidator extends RegularExpressionValidator
{
    /**
     * @var string 用户名正则, 首位不能为数字, 否则可能跟手机号重合, 也不能为邮箱地址
     */
    public $pattern = '/^[^\d][a-z0-9\x{4e00}-\x{9fa5}]{3,20}$/iu';

    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的用户名。';
}
