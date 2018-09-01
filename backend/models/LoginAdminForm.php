<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.08.18
 * Time: 13:32
 */

namespace backend\models;

use yii\base\Model;
use common\models\User;

class LoginAdminForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email','password'],'required'],
            ['email','email'],
            ['password','auth'],
        ];
    }

    public function auth($attributes){
        if(!$this->hasErrors()){
            $user = $this->getUser($this->email);
            if (sha1(substr($this->password, -3, 3) . mb_substr($this->password, 3, 4) . substr($this->password, 0, 3)) == $user->password && $user->type == 'admin'){
                return true;
            }else{
                $this->addError($attributes, 'Email или пароль введены неверно!');
                return false;
            }
        }else{
            return false;
        }
    }

    public function getUser($email){
        return User::findOne(['email'=> $email]);
    }
}