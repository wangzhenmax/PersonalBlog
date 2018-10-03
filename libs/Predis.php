<?php
namespace libs;
class Predis{
    private static $redis;
    private function __clone(){}
    private function __construct(){}
    static function getinterface(){
        $config=config("redis");
        self::$redis = new \Predis\Client([
            'scheme' => $config['scheme'],
            'host'   => $config['host'],
            'port'   => $config['port'],
        ]);
        return self::$redis;
    }
}