<?php
namespace libs;
class Predis{
    private static $redis;
    private function __clone(){}
    private function __construct(){}
    static function getinterface(){
        self::$redis = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ]);
        return self::$redis;
    }
}