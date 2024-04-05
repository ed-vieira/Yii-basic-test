<?php 

namespace app\models\traits;

trait AsArray {

    public function asArray(array $fields = [], array $expand = [], $recursive = true) {
        return $this->toArray($fields, $expand, $recursive);
    }

}