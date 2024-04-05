<?php 

namespace app\models\traits;

use yii\helpers\Url;

trait HasPhoto {

    public function getPhoto():? string {
        return $this->photo? Url::to($this->photo, true): null;
    }

}