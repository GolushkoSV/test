<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id){
        return self::findOne($id);
    }

    public function getId(){
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null){

    }

    public function getAuthKey(){

    }

    public function validateAuthKey($authKey){

    }

    //Генератор случайного пароля.
    function generatePassword($length)
    {

        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";

        $size = strlen($chars) - 1;
        $password = null;

        while ($length--) {
            $password .= $chars[rand(0, $size)];
        }
        return $password;
    }


}
