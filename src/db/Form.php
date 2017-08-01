<?php

namespace revolver\components\db;

use app\components\service\ServiceException;
use ArrayObject;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\InvalidParamException;
use yii\helpers\Inflector;
use yii\validators\Validator;

/**
 * 表单模型基类
 */
class Form extends Model
{
    /**
     * @inheritdoc
     */
    public function getAttributeLabel($attribute)
    {
        $label = parent::getAttributeLabel($attribute);

        if ($label == $this->generateAttributeLabel($attribute)) {
            $class = new ReflectionClass($this);
            if ($class->hasProperty($attribute)) {
                $property = $class->getProperty($attribute);
                if ($property->isPublic()) {
                    $docBlock = $property->getDocComment();
                    if (preg_match('/@var\s+[a-z0-9]+\s+([^,，.。\s]+)/iu', $docBlock, $match)) {
                        $label = $match[1];
                    }
                }
            }
        }

        return $label;
    }

    /**
     * 将下划线命名的字段映射到驼峰格式, 以便表单对象字段赋值
     *
     * @inheritdoc
     */
//    public function setAttributes($values, $safeOnly = true)
//    {
//        if (is_array($values)) {
//            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
//            foreach ($values as $name => $value) {
//                $name = Inflector::variablize($name);
//                if (isset($attributes[$name])) {
//                    $this->$name = $value;
//                } elseif ($safeOnly) {
//                    $this->onUnsafeAttribute($name, $value);
//                }
//            }
//        }
//    }

    /**
     * 返回非空(!== null)的字段, 并且将驼峰转为下划线格式
     *
     * @inheritdoc
     */
    public function getAttributes($names = null, $except = [])
    {
        $attributes = parent::getAttributes($names, $except);
        $attributes = array_filter($attributes, function ($value) {
            return $value !== null;
        });
        $cleanAttributes = [];

        foreach ($attributes as $name => $value) {
            $cleanAttributes[Inflector::underscore(($name))] = $value;
        }

        return $cleanAttributes;
    }

    /**
     * 验证场景
     *
     * @param array $data
     * @param null $scenario
     */
    public function validateScenario(array $data, $scenario = null)
    {
        if ($scenario) {
            $this->setScenario($scenario);
        }

        $this->load($data, '');

        if (!$this->validate()) {
            $attribute = key($this->getErrors());
            $error = parent::getFirstError($attribute);


            if (strpos($error, ' ')) {
                list($code, $message) = explode(' ', $error, 2);

                if (preg_match('/^[1-9]\d+$/', $code)) {
                    throw new ServiceException($message, (int)$code);
                }
            }

            throw new ServiceException($error);
        }
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        if (!$this->beforeValidate()) {
            return false;
        }

        $scenarios = $this->scenarios();
        $scenario = $this->getScenario();
        if (!isset($scenarios[$scenario])) {
            throw new InvalidParamException("Unknown scenario: $scenario");
        }

        if ($attributeNames === null) {
            $attributeNames = $this->activeAttributes();
        }

        foreach ($this->getActiveValidators() as $validator) {
            $validator->validateAttributes($this, $attributeNames);

            if ($this->hasErrors()) {
                break;
            }
        }
        $this->afterValidate();

        return !$this->hasErrors();
    }

    /**
     * @inheritdoc
     */
    public function createValidators()
    {
        $validators = new ArrayObject;
        foreach ($this->rules() as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                $rule[1] = self::findCustomValidator($rule[1]);
                $validator = Validator::createValidator($rule[1], $this, (array)$rule[0], array_slice($rule, 2));
                $validators->append($validator);
            } else {
                $message = 'Invalid validation rule: a rule must specify both attribute names and validator type.';
                throw new InvalidConfigException($message);
            }
        }
        return $validators;
    }

    /**
     * 找出自定义的验证器
     *
     * @param $name
     * @return string
     */
    protected static function findCustomValidator($name)
    {
        $validator = null;

        if (class_exists('app\components\validators\Box')) {
            $validator = call_user_func(['app\components\validators\Box', 'get'], $name);

        }

        return $validator ?: $name;
    }
}
