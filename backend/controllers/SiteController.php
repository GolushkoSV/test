<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\LoginAdminForm;


class SiteController extends Controller
{
    public $layout = "basic";

    public function actionIndex(){
        Yii::$app->view->title = 'Администратор - вход';

        $model = new LoginAdminForm();

        if(isset($_POST['LoginAdminForm'])){

            $model->attributes = Yii::$app->request->post('LoginAdminForm');
            if($model->validate()){
                Yii::$app->user->login($model->getUser($model->email));
                return $this->redirect('index.php?r=cabinet/index');

            }
        }

        return $this->render('index',['model'=>$model]);
    }

    public function actionLogin(){
        return $this->render('login');
    }
}
