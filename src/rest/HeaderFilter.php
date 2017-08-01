<?php

namespace revolver\components\rest;

use Yii;
use yii\base\ActionFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;

/**
 * Access Filter
 *
 * token: TOKEN
 */
class HeaderFilter extends ActionFilter
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->checkHeaders(Yii::$app->request->headers);
        $this->setAppParams();
        return parent::beforeAction($action);
    }

    /**
     * 头部传参验证验证
     * @param $headers
     * @throws ForbiddenHttpException
     */
    public function checkHeaders($headers)
    {
        $contentType = Yii::$app->request->getHeaders()->get('content-type');
        if('application/json' !== $contentType){
            throw new ForbiddenHttpException('Please use the allowed head!');
        }
    }

    /**
     * 默认通过RowBody获取传递参数赋值到$params中
     * @param null $params
     */
    public function setAppParams()
    {
        Yii::$app->params = (array)ArrayHelper::merge( Json::decode( \Yii::$app->request->getRawBody(), true ), Yii::$app->params);
    }


}
