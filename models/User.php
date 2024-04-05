<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }


    public static function tableName() {
        return '{{users}}';
    }


    public function beforeSave($insert): bool {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
            }

            if (! $this->isNewRecord) {
                $this->updated_at = date('Y-m-d h:i:s');
            }
            return true;
        }
        return false;
    }


    public function setPassword(string $password): void {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }


    public function generateAuthKey(): void {
        $this->auth_key = $this->username.':'.Yii::$app->getSecurity()->generateRandomString();
    }

    public function generateAccessToken(): string {
        $this->access_token = $this->username.'@'.Yii::$app->getSecurity()->generateRandomString(100).':';
        $this->save();
        return $this->access_token;
    }

}
