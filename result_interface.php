<?php
abstract class BulkUpdateAcfResult
{
    protected $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }
    abstract public function addMessage($add);

    abstract public function sendMessage();
}
