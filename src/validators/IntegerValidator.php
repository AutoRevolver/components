<?php

namespace revolver\components\validators;

use yii\validators\NumberValidator;

/**
 * integer 验证器
 *
 * 官方的 NumberValidator 在 === '' 的时候直接忽略验证, 所以导致属性被赋值,
 * 然后 getAttributes 的时候没有将 === '' 的值排除掉, 就会被 model 写为 null, 如果数据表字段设置的是 not null, 就会错误。
 *
 * 对于这种错误, 一个是让客户端不要传递 === '' 的字段, 一个就是对 === '' 的字段进行忽略。这里采用第1种方案。
 */
class IntegerValidator extends NumberValidator
{
    /**
     * @var bool
     */
    public $integerOnly = true;

    /**
     * @var array
     */
    public $isEmpty = ['self', 'checkIsEmpty'];

    /**
     * @param $value
     * @return bool
     */
    public static function checkIsEmpty($value)
    {
        return $value === null;
    }
}
