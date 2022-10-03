<?php
class BulkUpdateAcfResult
{
    private $result;
    private $status;
    private $message;
    public function __construct(bool $result, int $status, string $message)
    {
        $this->result = $result;
        $this->status = $status;
        $this->message = $message;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
