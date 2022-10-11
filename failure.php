<?php
class BulkUpdateAcfResultFailure extends BulkUpdateAcfResult
{
    public function sendMessage ()
    {
        wp_die($this->message, '', 400);
    }

    public function isFailured()
    {
        return $this->message !== '';
    }
    public function addMessage($add, $raw = null)
    {
      if ($raw) {
        $this->message .= "{$raw}行目: {$add}" . '<br>';
      } else {
        $this->message .= $add . '<br>';
      }
    }
}
