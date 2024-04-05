<?php

namespace app\models;

use app\models\traits\AsArray;
use app\models\traits\HasPhoto;
use app\models\forms\UploadForm;
use Exception;
use yii\db\ActiveRecord;
use yii\helpers\BaseFileHelper as File;
use Yii;

class Product extends ActiveRecord {

    use AsArray, HasPhoto;


    public function getCustomer() {
       return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }


    public static function tableName() {
        return '{{products}}';
    }
    

    public function beforeSave($insert): bool {
        if (parent::beforeSave($insert)) {    
            if (! $this->isNewRecord) {
                $this->updated_at = date('Y-m-d h:i:s');
            }
            return true;
        }
        return false;
    }

    public function beforeDelete(): bool {
        try {
            File::removeDirectory("customer/{$this->customer_id}/products/{$this->id}");
        } catch (Exception $e) {
            Yii::debug($e->getMessage());
        }
        return parent::beforeDelete(); 
    }


    public function handleFormPhoto(): void {
        try {
            $this->photo = (new UploadForm())->uploadProductPhoto($this->customer_id, $this->id);
            $this->save();
        } catch (Exception $e) {
           throw $e;
        }
    }


    public function rules()
    {
        return [
            [['name', 'price',], 'required'],
            [
               'name', 'string', 'max' => 60, 
               'tooShort' => 'O campo [nome] é obrigatório', 
               'tooLong' => 'O campo [nome] pode ter no máximo 60 caracteres',
            ],
            [
                'price', 'integer', 'min' => 1,
                'tooSmall' => 'O campo [preço] deve ter no mínimo 1',
            ],
        ];  
    }

    public function asArray(array $fields = [], array $expand = [], $recursive = true): array {
        if (empty($fields) && !is_null($this->id)) {
            $arr['id'] = $this->id;
            $arr['name'] = $this->name;
            $arr['customer_id'] = $this->customer_id; 
            $arr['price'] = $this->price;
            $arr['photo'] = $this->getPhoto();
            return $arr;
        } else {
            return $this->toArray($fields, $expand, $recursive);
        }
    }


}


