<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class CustomerAddress extends ActiveRecord {


    public function getCustomer() {
        return $this->hasOne(Customer::class, ['customer_id' => 'id']);
    }

    public static function tableName() {
        return '{{customer_address}}';
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


    public function rules()
    {
        return [
            [['customer_id', 'post_code', 'state', 'city', 'street', 'number'], 'required'],
            [
               'post_code', 'string', 'min' => 8, 'max' => 9, 
               'tooShort' => 'O campo [cep] deve ter no mínimo 8 caracteres', 
               'tooLong'  => 'O campo [cep] pode ter no máximo 9 caracteres',
            ],
            [
                'state', 'string', 'min' => 2, 'max'=> 2, 
                'tooShort' => 'O campo [uf] deve ter no mínimo 2 caracteres',
                'tooLong'  => 'O campo [uf] pode ter no máximo 2 caracteres',
            ],
            [
                'city', 'string', 'min' => 1, 'max' => 60, 
                'tooShort' => 'O campo [cidede] é obrigatório',
                'tooLong'  => 'O campo [cidade] deve ter no máximo 60 caracteres',
            ],
            [
                'street', 'string', 'min' => 1, 'max' => 60, 
                'tooShort' => 'O campo [rua] é obrigatório',
                'tooLong'  => 'O campo [rua] deve ter no máximo 60 caracteres',
            ],
            [
                'number', 'string', 'min' => 1,  'max' => 12, 
                'tooShort' => 'O campo [numero] é obrigatório',
                'tooLong'  => 'O campo [numero] deve ter no máximo 12 caracteres',
            ],
            [   
                'complement', 'string', 'max' => 200,
                'tooLong'  => 'O campo [complemento] pode ter no máximo 200 caracteres',
            ]
        ];  
    }


}


