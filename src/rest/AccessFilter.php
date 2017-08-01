<?php

namespace revolver\components\rest;

use Yii;
use yii\base\ActionFilter;
use app\components\service\ServiceException;

/**
 * Access Filter
 *
 * token: TOKEN
 */
class AccessFilter extends ActionFilter
{
    private $accessKeyId = false;
    private $accessKeySecret = false;


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
//        $this->checkToken(Yii::$app->request->headers);
        return parent::beforeAction($action);
    }

    /**
     * Token验证
     * @param $headers
     * @throws ForbiddenHttpException
     */
    public function checkToken($headers)
    {
        $this->accessKeyId = addslashes($headers->get('accessKeyId'));
        $this->accessKeySecret = addslashes($headers->get('accessKeySecret'));

        if(!$this->accessKeyId) throw new ServiceException('获取不到accessKeyID', 100001);
        if(!$this->accessKeySecret) throw new ServiceException('获取不到accessKeySecret', 100002);

        $ok = $this->checkTokenInRedis() ? true : $this->checkTokenInDB() ? true : false;

        if(!$ok){
            throw new ServiceException('accessKey校验错误', 100003);
        }

    }

    public function checkTokenInRedis()
    {
        $res = Yii::$app->redis;
        return  $res->get($this->accessKeyId) === $this->accessKeySecret ;
    }

    public function checkTokenInDB()
    {
        $res = Yii::$app->redis;
        $res->set($this->accessKeyId, $this->accessKeySecret);
    }


}
