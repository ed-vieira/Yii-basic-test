<?php 

namespace app\services\products;

use app\interfaces\contracts\IRepository;
use app\repositories\products\Repository;
use app\interfaces\classes\service\Service as Base;

class Service extends Base implements IRepository {

    public static function new() {
       return new static(new Repository);
    }

}