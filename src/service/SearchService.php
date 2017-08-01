<?php
/**
 * Created by PhpStorm.
 * User: Justin
 * Date: 2017/7/18
 * Time: 16:08
 */
namespace revolver\components\service;

use yii\db\Query;

class SearchService extends BaseService
{

    /**
     * 根据搜索配置规则后，获取列表，其中插入了分页
     * @return mixed
     */
    public function getList()
    {
        $M = $this->getDefauleModel();
        $this->setEnumsByModel($M);

        $Query = new Query();
        $this->setSearch($Query);

        $list = $this->pageQuery(
            $Query,
            $M::getDb(),
            $this->form->page,
            $this->form->size
        );

        return $list;
    }


    /**
     * 根据配置规则，获取详情
     * @return mixed
     */
    public function getDetail()
    {
        $M = $this->getDefauleModel();
        $this->setEnumsByModel($M);

        if($this->getRulesBySearchOps){
            $Query = new Query();
            $this->setSearch($Query);
            return $Query->one($M::getDb());
        }else{
            return $M::findOne($this->form->id);
        }

    }

    /**
     * 搜索规则执行
     * @param $Query
     */
    public function setSearch(&$Query)
    {
        // from 处理
        if($this->getSearchOp('from')){
            $Query->from($this->getSearchOp('from'));
        }
        // select 处理
        if($this->getSearchOp('select')){
            $Query->select($this->getSearchOp('select'));
        }
        // join处理
        if($this->getSearchOp('join')){
            foreach ($this->getSearchOp('join') as $joinArr) {
                list($a, $b, $c) = $joinArr;
                $Query->join($a, $b, $c);
            }
        }
        // andWhere处理
        if($this->getSearchOp('andWhere')){
            foreach ($this->getSearchOp('andWhere') as $andWhere) {
                $Query->andWhere($andWhere);
            }
        }
        // andFilerwhere处理
        if($this->getSearchOp('andFilterWhere')){
            foreach ($this->getSearchOp('andFilterWhere') as $andFilerWhere) {
                $Query->andFilterWhere($andFilerWhere);
            }
        }
        // orderBy处理
        if($this->getSearchOp('orderBy')){
            $Query->orderBy($this->getSearchOp('orderBy'));
        }
        // groupBy处理
        if($this->getSearchOp('groupBy')){
            $Query->groupBy($this->getSearchOp('groupBy'));
        }

    }

    /**
     * 搜索规则配置
     * @$searchOps null|array  配置文件
     * @$getRulesBySearchOps bool  使用配置开关
     */
    protected $searchOps = null;
    protected $getRulesBySearchOps = false;
    public function setSearchOps($searchOp)
    {
        $this->getRulesBySearchOps = true;
        $this->searchOps = array_merge([
            'select' => '*',
            'join' => null,
            'andWhere' => null,
            'andFilterWhere' => null,
            'orderBy' => null,
            'groupBy' => null
        ], $searchOp);
    }
    public function getSearchOps()
    {
        return $this->searchOps;
    }
    public function setSearchOp($opName, $value)
    {
        $this->getRulesBySearchOps = true;
        $this->searchOps[$opName] = $value;
    }
    public function getSearchOp($opName)
    {
        return isset($this->searchOps[$opName]) ? $this->searchOps[$opName] : null;
    }


    /**
     * @$Enums 枚举数组
     */
    static public $enums;
    static public function getEnums()
    {
        return static::$enums;
    }
    static public function setEnums($enums)
    {
        static::$enums = $enums;
    }
    public function setEnumsByModel($Model)
    {
        $enumPart = $Model::className();
        static::$enums[ strtolower(str_replace('Model', '', substr($enumPart, strrpos($enumPart, '\\')+1))) ] = $Model->getEnums();
    }

}