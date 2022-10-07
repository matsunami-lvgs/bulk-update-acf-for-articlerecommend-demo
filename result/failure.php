<?php
class BulkUpdateAcfResultFailure extends BulkUpdateAcfResult
{
    public function sendMessage ()
    {
        wp_die($this->message, '', 400);
    }

    public function isFailured ()
    {
        return empty($this->message);
    }
}
