<?php

namespace revolver\components\validators;

use yii\validators\RangeValidator;

/**
 * In Validator
 *
 * 对官方的 RangeValidator 扩展修改, 只支持字符串值
 *
 * 这个同 IntegerValidator 有相同的问题, 详细的跳过去看。
 *
 * 同时因为php的弱类型问题, 比较的时候  '' = 0 是成立的。 如果设置 strict = true, 那传递的参数值都是字符串, 那么跟数值比较就是失败的。
 *
 * 因为我们一般设置 'range' => [0,1,2,3] 这样, 所以最好的办法就是, 比较基于字符串格式, === '' 的直接报错。
 */
class InValidator extends RangeValidator
{
    /**
     * @var bool
     */
    public $strict = true;

    /**
     * @var array
     */
    public $isEmpty = ['self', 'checkIsEmpty'];

    /**
     * 将 value 和 range 都转为 string
     *
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (is_array($value)) {
            foreach ($value as &$row) {
                $row = (string)$row;
            }
            unset($row);
        } else {
            $value = (string)$value;
        }

        foreach ($this->range as &$row) {
            $row = (string)$row;
        }
        unset($row);

        return parent::validateValue($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public static function checkIsEmpty($value)
    {
        return $value === null;
    }
}
