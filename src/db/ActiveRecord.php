<?php

namespace revolver\components\db;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * ActiveRecord 基础类
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 枚举存储
     * @var array
     */
    protected $enums = [];

    /**
     * 根据id获取枚举名称
     */
    public function getEnumVal($part, $key)
    {
        return ArrayHelper::getValue(ArrayHelper::getValue($this->enums, $part, []), $key, null);
    }

    /**
     * 根据名称获取枚举id
     */
    public function getEnumKey($part, $val)
    {
        $result = ArrayHelper::getValue($this->enums, $part, null);
        return $result ? ArrayHelper::getValue(array_flip($result), $val) : null;
    }

    /**
     * 获取枚举数组
     */
    public function getEnum($part)
    {
        return ArrayHelper::getValue($this->enums, $part, []);
    }

    /**
     * 设置枚举
     * @param $partName
     * @param $arr
     */
    public function setEnum($partName, $arr)
    {
        $this->enums[$partName] = $arr;
    }

    /**
     * 获取所有枚举
     */
    public function getEnums()
    {
        return $this->enums;
    }

    /**
     * 设置所有枚举
     */
    public function setEnums($arr)
    {
        $this->enums = $arr;
    }



}
