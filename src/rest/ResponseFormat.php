<?php

namespace revolver\components\rest;

use yii\base\Component;
use yii\helpers\Json;

/**
 * 接口响应类
 *
 * return Response(['code' => 100, 'message' => 'xxx', 'data' => null]);
 *
 */
class ResponseFormat extends Component
{
    /**
     * @var int Api Code
     */
    private $code = 0;

    /**
     * @var string Api Message
     */
    private $message = '请求成功';

    /**
     * @var mixed Api Data
     */
    private $data = null;


    /**
     * @var mixed Api Enum
     */
    private $enum = null;

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get Data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get Enum
     *
     * @return mixed
     *
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * Set Code
     *
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = (int)$code;
    }

    /**
     * Set Message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = (string)$message;
    }

    /**
     * Set Data
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function setEnum($enum)
    {
        $this->enum = $enum;
    }

    /**
     * setApi
     */
    public function setApiResponse()
    {
        // 设置返回格式
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        // 设置头部信息
        $headers = $response->headers;
        $headers->removeAll();
        $headers->add("Content-type","application/json;charset=utf-8");
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
            'enum' => $this->getEnum()
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->setApiResponse();
        return Json::encode($this->toArray());
    }
}
