<?php
/**
 * User: david marsalone
 * Date: 15/09/2017
 * Time: 11:04
 */

namespace Dubture\Monolog\Reader;

/**
 * Possible extra fields according  processors
 * @package Dubture\Monolog\Reader
 */
class LineLogExtra
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $ip;
    /**
     * @var string
     */
    public $http_method;
    /**
     * @var string
     */
    public $server;
    /**
     * @var string
     */
    public $referrer;
    /**
     * @var string
     */
    public $unique_id;
    /**
     * @var string
     */
    public $file;
    /**
     * @var string
     */
    public $line;
    /**
     * @var string
     */
    public $class;
    /**
     * @var string
     */
    public $function;

    /**
     * LineLogExtra constructor.
     * @param array $extra extra Array from log line
     */
    public function __construct(array $extra)
    {
        foreach ($extra as $k=>$v){
            $this->$k=$v;
        }
    }

}