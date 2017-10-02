<?php
/**
 * User: david marsalone
 * Date: 15/09/2017
 * Time: 10:53
 */
namespace Dubture\Monolog\Reader;

/**
 * Just to get it OOP
 * @package Dubture\Monolog\Reader
 * @link https://github.com/pulse00/monolog-parser
 */
class LineLog
{
    /**
     * @var \DateTime Log date :)
     */
    public $date;
    /**
     * @var string
     */
    public $logger;
    /**
     * @var string
     */
    public $level;
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $context;
    /**
     * @var LineLogExtra
     */
    public $extra;
    /**
     * LineLog constructor.
     * @param array $line A line from LogReader
     */
    public function __construct(array $line)
    {
        foreach ($line as $k=>$v){
            $this->$k=$v;
        }
        if($this->date==null){
            $this->date=\DateTime::createFromFormat("Y-m-d H:i:s","0000-01-01 00:00:00");
        }
        if(isset($line["extra"]) && is_array($line["extra"])){
            $this->extra=new LineLogExtra($line["extra"]);
        }
    }
}