<?php


namespace exchanged;


class App
{
    public static $app;

    public function __construct()
    {
        $query = trim($_SERVER['REQUEST_URI'], '/');
        session_start();
        self::$app = Registry::instance();
        self::getParams();
        new ErrorHandler();
        Router::dispatch($query);
    }

    public function getParams(){
        $paramsFile = ROOT. '/config/params.php';
        if (!empty($paramsFile)) {
            $params = require_once $paramsFile;
            foreach ($params as $k => $v) {
                self::$app->setProper($k, $v);
            }
        }
    }
}