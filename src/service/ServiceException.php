<?php

namespace revolver\components\service;

use yii\base\Exception;

/**
 * 服务异常类
 */
class ServiceException extends Exception
{

    /**
     * 获取类名
     * @return string
     */
    public function getName()
    {
        return 'ServiceException';
    }

    /**
     * @inheritdoc
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $code = (int)$code;

        if ($code < 1) {
            $code = 1;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * 返回 response
     *
     * @return Response
     */
//    public function response()
//    {
//        return new Response(['message' => $this->message, 'code' => $this->code]);
//    }
}
