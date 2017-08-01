<?php
namespace revolver\components\tools;

class Redis extends \yii\base\Component
{
    public $host;
    public $port;
    public $charset;

    public  $hdl;       // connection handler
    public  $connect_on;        // connection status


    function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->hdl = new \Redis();

        if ($this->hdl->connect($this->host, $this->port, 2.5)) {
            $this->connect_on   = true;
        }
        else {
            $this->connect_on   = false;
        }
    }

    /**
     * destroy class
     */
    function __destruct()
    {
        if ($this->connect_on) {
            $this->hdl->close();
            $this->connect_on   = false;
        }
    }

    /**
     * destroy class
     */
    public function destory()
    {
        if ($this->connect_on) {
            $this->hdl->close();
            $this->connect_on   = false;
        }
    }

    /**
     * return result from user's call
     */
    public function __call($name, $args)
    {
        if ('flush' == substr($name, 0, 5)) {
            return false;
        }
        if ($this->connect_on && method_exists($this->hdl, $name)) {
            // Invoke redis class.
            return call_user_func_array(array($this->hdl, $name), $args);
        }
        return false;
    }

}
