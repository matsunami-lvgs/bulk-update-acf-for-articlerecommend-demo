<?php
class BulkUpdateAcfFile extends SplFileObject
{
    /**
     * @return array[
     * 'line',
     * 'targt_id',
     * 'related_id' 
     * ]
     */
    public function fetchCsv()
    {
        $this->setFlags(
            SplFileObject::READ_CSV|
            SplFileObject::READ_AHEAD|
            SplFileObject::SKIP_EMPTY|
            SplFileObject::DROP_NEW_LINE
        );
        $arr = [];

        foreach ($this as $line) {
            if ($this->key() !== 0) {
                $target_id = $line[0];
                unset($line[0]);
                $related_ids = array_values($line);
                $arr[] = [
                    'line' => $this->key() + 1,
                    'target_id' => $target_id,
                    'related_ids' => $related_ids,
                ];
            }
        }
        return $arr;
    }
}
