<?php
abstract class BulkUpdateAcfResult
{
    private $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }
    public function addMessage(string $add)
    {
        return new static($this->message . PHP_EOL . $add);
    }

    abstract public function sendMessage();
}
