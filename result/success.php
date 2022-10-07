<?php
class BulkUpdateAcfResultSuccess extends BulkUpdateAcfResult
{
    public function sendMessage ()
    {
        echo $this->message;
    }
}
