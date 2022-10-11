<?php
class BulkUpdateAcfResultSuccess extends BulkUpdateAcfResult
{
    public function sendMessage ()
    {
        echo $this->message;
    }

    public function addMessage($add)
    {
        $this->message .= $add . '<br>';
    }
}
