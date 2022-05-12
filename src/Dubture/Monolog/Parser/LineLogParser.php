<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\Monolog\Parser;

/**
 * Class LineLogParser
 * @package Dubture\Monolog\Parser
 */
class LineLogParser implements LogParserInterface
{
    /**
     * @var string
     */
    protected $pattern = array(
        'default' => '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/',
        'error'   => '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>(.*)+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/'
    );


    /**
     * @param string $log
     * @param int    $days
     * @param string $pattern
     *
     * @return array
     */
    public function parse($log, $days = 1, $pattern = 'default')
    {
        if (!is_string($log) || strlen($log) === 0) {
            return array();
        }

        preg_match($this->pattern[$pattern], $log, $data);


        $date=null;
        if (!isset($data['date'])) {
            return array();
        }else{
            $date=$data['date'];
            $date=preg_replace("/([0-9]{4}-[0-9]{2}-[0-9]{2}).*([0-9]{2}:[0-9]{2}:[0-9]{2}).*/","$1 $2",$date);
            $date=\DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }

        $array = array(
            'date'    => $date,
            'logger'  => $data['logger'] ?? null,
            'level'   => $data['level'] ?? null,
            'message' => $data['message'] ?? null,
            'context' => json_decode($data['context'] ?? '', true),
            'extra'   => json_decode($data['extra'] ?? '', true)
        );

        if (0 === $days) {
            return $array;
        }

        if (isset($date) && $date instanceof \DateTime) {
            $d2 = new \DateTime('now');

            if ($date->diff($d2)->days < $days) {
                return $array;
            } else {
                return array();
            }
        }
    }

    /**
     * @param string $name
     * @param string $pattern
     *
     * @throws \RuntimeException
     */
    public function registerPattern($name, $pattern)
    {
        if (!isset($this->pattern[$name])) {
            $this->pattern[$name] = $pattern;
        } else {
            throw new \RuntimeException("Pattern $name already exists");
        }
    }
}
