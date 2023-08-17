<?php

namespace storfollo\gatetracker;

class LogIteratorHeader extends LogIterator
{
    private array $header;

    protected function readHeader()
    {
        $pos = ftell($this->fp);
        fseek($this->fp, 0);
        $header = fgetcsv($this->fp, separator: "\t");
        if ($pos != 0)
            fseek($this->fp, $pos);
        return $header;
    }

    protected function read()
    {
        if (empty($this->header))
            $this->header = $this->readHeader();

        $data = fgetcsv($this->fp, separator: "\t");
        return array_combine($this->header, $data);
    }

}