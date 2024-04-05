<?php 

namespace app\interfaces\contracts;

use yii\base\Model;

interface IErrors {

    public function getErrors(): array;

}