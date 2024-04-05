<?php 

namespace app\services\customers;

use app\interfaces\contracts\IRepository;
use app\repositories\customers\Repository;
use app\interfaces\classes\service\Service as Base;

class Service extends Base implements IRepository {

    public static function new() {
       return new static(new Repository);
    }

}