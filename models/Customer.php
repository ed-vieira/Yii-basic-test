<?php

namespace app\models;

use Exception;
use yii\db\ActiveRecord;
use app\models\forms\UploadForm;
use app\models\traits\AsArray;
use app\models\traits\HasPhoto;
use app\utils\strings\Handler as StrHandler;
use yii\helpers\BaseFileHelper as File;

use Yii;

class Customer extends ActiveRecord {


    use AsArray, HasPhoto;

    public function getProducts() {
       return $this->hasMany(Product::class, ['customer_id' => 'id']);
    }

    public function getAddress() {
        return $this->hasOne(CustomerAddress::class, ['customer_id' => 'id']);
    }

    public static function tableName() {
        return '{{customers}}';
    }

    public function handleFormPhoto(): void {
        try {
            $this->photo = (new UploadForm())->uploadCustomerPhoto($this->id);
            $this->save();
        } catch (Exception $e) {
           throw $e;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            File::createDirectory("customer/{$this->id}/profile");
        }
        parent::afterSave($insert, $changedAttributes);
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
            File::removeDirectory("customer/{$this->id}");
        } catch (Exception $e) {
            Yii::debug($e->getMessage());
        }
        return parent::beforeDelete(); 
    }





    public function asArray(array $fields = [], array $expand = [], $recursive = true): array {
        if (empty($fields) && !is_null($this->id)) {
            $arr['id'] = $this->id;
            $arr['name'] = $this->name; 
            $arr['cpf'] = $this->cpf;
            $arr['gender'] = $this->gender;
            $arr['photo'] = $this->getPhoto();
            if (! is_null($this->address)) {
                $arr['address']['post_code'] = $this->address->post_code;
                $arr['address']['country'] = $this->address->country;
                $arr['address']['state'] = $this->address->state;
                $arr['address']['city'] = $this->address->city;
                $arr['address']['street'] = $this->address->street;
                $arr['address']['number'] = $this->address->number;
                $arr['address']['complement'] = $this->address->complement;
            }
            $arr['products'] = $this->products;
            return $arr;
        } else {
            return $this->toArray($fields, $expand, $recursive);
        }
    }



    public function rules()
    {
        return [
            [['name', 'cpf', 'gender', 'cpf_number'], 'required'],
            [
               'name', 'string', 'max' => 60, 
               'tooShort' => 'O campo [nome] é obrigatório', 
               'tooLong' => 'O campo [nome] pode ter no máximo 60 caracteres',
            ],
            [
                'cpf', 'string', 'min' => 11, 'max' => 14, 
                'tooShort' => 'O campo [cpf] deve ter no mínimo 11 caracteres',
                'tooLong'  => 'O campo [cpf] pode ter no máximo 14 caracteres',
            ],
            [
                'cpf', 'validateCPF'
            ],
            [
                'gender', 'string', 'min' => 1, 'max' => 1, 
                'tooShort' => 'O  campo [sexo] é obrigatório',
                'tooLong'  => 'O campo  [sexo] deve ter no máximo 1 caracter (m) Masculino | (f) Feminino',
            ],
        ];  
    }


    public function validateCPF() {
        if (! StrHandler::validateCPF($this->cpf)) {
            $this->addError($this->cpf, 'CPF inválido');
            return false;
        }
        return true;
    }



}


