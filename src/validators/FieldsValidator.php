<?php

namespace revolver\components\validators;

use yii\validators\Validator;

/**
 * 返回字段验证器
 */
class FieldsValidator extends Validator
{
    /**
     * @var string 错误信息
     */
    public $message = '请输入正确的字段列表。';

    /**
     * @var array 范围, 可以是数组, 也可以是可调用结构
     */
    public $range = [];

    /**
     * @var string 字段分隔符
     */
    public $separator = ',';

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!is_string($value)) {
            $valid = false;
        } else {
            $fields = explode($this->separator, $value);

            $valid = array_diff($fields, $this->range) ? false : true;
        }

        return $valid ? null : [$this->message, []];
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if (is_callable($this->range)) {
            $this->range = call_user_func($this->range);
        }

        parent::validateAttribute($model, $attribute);
    }
}
