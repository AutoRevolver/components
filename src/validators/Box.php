<?php

namespace revolver\components\validators;

/**
 * 验证器入口
 */
class Box
{
    /**
     * @param $name
     * @return null|string
     */
    public static function get($name)
    {
        if (!$name) {
            throw new \InvalidArgumentException('Validator name can not be empty.');
        }

        $validatorClass = __NAMESPACE__ . '\\' . ucfirst($name) . 'Validator';

        return class_exists($validatorClass) ? $validatorClass : null;
    }
}
