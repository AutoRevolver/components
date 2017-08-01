<?php

namespace revolver\components\validators;

use yii\validators\RegularExpressionValidator;

/**
 * 固定电话验证器
 */
class TelValidator extends RegularExpressionValidator
{
    /**
     * @var string 固定电话正则
     */
    public $pattern = '/^(0\d{2,3}(\-)?)?\d{7,8}(\-\d+)?$/';

    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的固定电话。';
}
