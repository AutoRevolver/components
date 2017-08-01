<?php

namespace revolver\components\rest;

use app\components\service\ServiceException;

class ErrorHandler extends \yii\base\ErrorHandler
{


    protected function renderException($exception)
    {
        if($exception instanceof ServiceException){
            $statusCode = 200;
            $code = $exception->getCode();
            $msg = $exception->getMessage();
        }else{
            $code = $statusCode = 500;
            $msg = 'prd' === YII_ENV ? 'SYSTEM ERROR' : $exception->getMessage();
        }

        $this->doResoponse(new ResponseFormat(
            [
                'code' => $code,
                'message' => $msg,
                'data' => null,
            ], $statusCode)
        );
    }

    /**
     * 执行返回
     * @param ResponseFormat $err
     */
    private function doResoponse(ResponseFormat $err, $statusCode = 200)
    {
        $res = \Yii::$app->response;
        $res->data = $err;
        $res->statusCode = $statusCode;
        $res->send();
    }


}
