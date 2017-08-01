<?php

namespace revolver\components\validators;

use yii\validators\RegularExpressionValidator;

/**
 * 邮政编码验证器
 */
class ZipValidator extends RegularExpressionValidator
{
    /**
     * @var string 邮政编码正则
     */
    public $pattern = '/^[1-9]\d{5}$/';

    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的邮政编码。';
}
