<?php

namespace revolver\components\validators;

use yii\validators\RegularExpressionValidator;

/**
 * QQ验证器
 */
class QqValidator extends RegularExpressionValidator
{
    /**
     * @var string QQ正则
     */
    public $pattern = '/^[1-9]{1}\d{4,9}$/';

    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的QQ号码。';
}
