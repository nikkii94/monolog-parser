<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\Monolog\Reader\Test;

use Dubture\Monolog\Parser\LineLogParser;

/**
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function logLineProvider()
    {
        return array(
            'simple' => array(
                'aha',
                'DEBUG',
                'foobar',
                array(),
                array(),
                '[%s] aha.DEBUG: foobar [] []'
            ),
            'default' => array(
                'test',
                'INFO',
                'foobar',
                array('foo' => 'bar'),
                array(),
                '[%s] test.INFO: foobar {"foo":"bar"} []'
            ),
            'multi_context' => array(
                'context',
                'INFO',
                'multicontext',
                array(array('foo' => 'bar'), array('bat' => 'baz')),
                array(),
                '[%s] context.INFO: multicontext [{"foo":"bar"},{"bat":"baz"}] []'
            ),
            'multi_context_empty' => array(
                'context',
                'INFO',
                'multicontext',
                array(array('foo' => 'bar'), array()),
                array(),
                '[%s] context.INFO: multicontext [{"foo":"bar"},[]] []'
            ),
            'multi_context_spaces' => array(
                'context',
                'INFO',
                'multicontext',
                array(array('foo' => 'bar', 'stuff' => 'and things'), array('bat' => 'baz')),
                array(),
                '[%s] context.INFO: multicontext [{"foo":"bar","stuff":"and things"},{"bat":"baz"}] []'
            ),
            'multi_context_message_spaces' => array(
                'context',
                'INFO',
                'multicontext with spaces',
                array(array('foo' => 'bar', 'stuff' => 'and things'), array('bat' => 'baz')),
                array(),
                '[%s] context.INFO: multicontext with spaces [{"foo":"bar","stuff":"and things"},{"bat":"baz"}] []'
            ),
            'extra_context' => array(
                'extra',
                'INFO',
                'context and extra',
                array(array('foo' => 'bar', 'stuff' => 'and things'), array('bat' => 'baz')),
                array(array('weebl' => 'bob'), array('lobob' => 'lo')),
                '[%s] extra.INFO: context and extra [{"foo":"bar","stuff":"and things"},{"bat":"baz"}] [{"weebl":"bob"},{"lobob":"lo"}]'
            ),
        );
    }

    /**
     * @dataProvider logLineProvider
     */
    public function testLineFormatter($logger, $level, $message, $context, $extra, $line)
    {
        $now     = new \DateTime();
        $parser  = new LineLogParser();
        $logLine = sprintf($line, $now->format('Y-m-d H:i:s'));
        
        $log     = $parser->parse($logLine);

        $this->assertInstanceOf('\DateTime', $log['date']);
        $this->assertEquals($logger, $log['logger']);
        $this->assertEquals($level, $log['level']);
        $this->assertEquals($message, $log['message']);
        $this->assertEquals($context, $log['context']);
        $this->assertEquals($extra, $log['extra']);
    }
}
