<?php

namespace revolver\components\validators;

use yii\validators\Validator;

/**
 * 密码验证器
 */
class PasswordValidator extends Validator
{
    /**
     * 最短长度
     */
    const MIN = 6;

    /**
     * 最大长度
     */
    const MAX = 20;

    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的密码。';

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $valid = false;
        $length = mb_strlen($value);

        if ($length >= self::MIN && $length <= self::MAX) {
            $valid = true;
        }

        return $valid ? null : [$this->message, []];
    }
}
