<?php 

namespace app\models\forms;

use Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper as File;

class UploadForm extends Model
{

    protected $file;
    public $photo;

    public function rules() {
        return [
           [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function uploadCustomerPhoto(int $customerId):? string {
        try {
            return $this->uploadFile("customer/{$customerId}/profile");
        } catch (Exception $e) {
            throw $e;  
        }
    }


    public function uploadProductPhoto(int $customerId, int $productId): string {
        try {
            return $this->uploadFile("customer/{$customerId}/products/{$productId}");
        } catch (Exception $e) {
            throw $e;  
        }
    }



    public function uploadFile(string $filePath) {
        try {
            $this->file = UploadedFile::getInstanceByName('photo');
            File::createDirectory($filePath);
            $file = "{$filePath}/{$this->file->baseName}.{$this->file->extension}";
            return $this->file->saveAs($file)? $file : false;
        } catch (Exception $e) {
            throw $e;  
        }
    }


}