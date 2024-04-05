<?php 

namespace app\interfaces\contracts;

use yii\base\Model;

interface IWriter {

    public function store(array $data);

    public function update(int $id, array $input);

    public function destroy(int $id);

}