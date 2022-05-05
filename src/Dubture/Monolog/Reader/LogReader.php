<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\Monolog\Reader;

use ArrayAccess;
use Countable;
use Dubture\Monolog\Parser\LineLogParser;
use Dubture\Monolog\Parser\LogParserInterface;
use Iterator;
use RuntimeException;
use SplFileObject;

/**
 * Class LogReader
 *
 * @package Dubture\Monolog\Reader
 */
class LogReader extends AbstractReader implements Iterator, ArrayAccess, Countable
{
    protected SplFileObject $file;
    protected int $lineCount;
    protected LogParserInterface|LineLogParser $parser;

    public int $days;
    public string $pattern;


    /**
     * @param        $file
     * @param int    $days
     * @param string $pattern
     */
    public function __construct($file, $days = 1, $pattern = 'default')
    {
        $this->file = new SplFileObject($file, 'r');
        $i = 0;
        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            $i++;
        }

        $this->days = $days;
        $this->pattern = $pattern;

        $this->lineCount = $i;
        $this->parser = $this->getDefaultParser($days, $pattern);
    }

    /**
     * @return LineLogParser|LogParserInterface
     */
    public function getParser()
    {
        $p =  &$this->parser;

        return $p;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern = 'default')
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->lineCount < $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        $key = $this->file->key();
        $this->file->seek($offset);
        $log = $this->current();
        $this->file->seek($key);
        $this->file->current();

        return $log;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new RuntimeException("LogReader is read-only.");
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new RuntimeException("LogReader is read-only.");
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->file->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        $this->file->next();
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return $this->parser->parse($this->file->current(), $this->days, $this->pattern);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): mixed
    {
        return $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->file->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->lineCount;
    }
}
