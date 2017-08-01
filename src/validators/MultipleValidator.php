<?php

namespace revolver\components\validators;

use yii\validators\Validator;
use yii\base\InvalidConfigException;

/**
 * Multiple Validator
 *
 * 多个值(例如 ids=1,2,3)的验证, 通过设置匹配模式。
 */
class MultipleValidator extends Validator
{
    /**
     * @var string 字段分隔符
     */
    public $separator = ',';

    /**
     * @var int 最多多少个
     */
    public $max = 100;

    /**
     * @var string 匹配模式
     */
    public $pattern;

    /**
     * @var string 错误信息
     */
    public $message = '{attribute}非法。';

    /**
     * @var string 个数超过限制错误信息
     */
    public $tooMany = '{attribute}最多 {max} 个。';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->pattern === null) {
            throw new InvalidConfigException('The "pattern" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!is_string($value) && !is_numeric($value)) {
            return [$this->message, []];
        }

        $values = explode($this->separator, $value);

        if (count($values) > $this->max) {
            return [$this->tooMany, ['max' => $this->max]];
        }

        foreach ($values as $row) {
            if (!preg_match($this->pattern, $row)) {
                return [$this->message, []];
            }
        }

        return null;
    }
}
