<?php

namespace storfollo\gatetracker;

/**
 * A generic log file iterator class
 */
class LogIterator implements \Iterator
{

    /**
     * @var false|resource
     */
    protected $fp;
    private int $size;

    public function __construct($file)
    {
        $this->fp = fopen($file, 'r');
        $this->size = filesize($file);
    }

    protected function read()
    {
        return fgetcsv($this->fp, separator: "\t");
    }

    public function current()
    {
        return $this->read();
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function key()
    {
        return ftell($this->fp);
    }

    public function valid()
    {
        return ftell($this->fp) < $this->size;
        return $this->fp !== false;
    }

    public function rewind()
    {
        fseek($this->fp, 0);
    }
}