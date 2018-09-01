<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LoginUserForm;


class SiteController extends Controller
{
    public $layout = 'basic';

    public function actionIndex(){
        $this->view->title = 'Авторизация';
        $model = new LoginUserForm();

        if(!Yii::$app->user->isGuest) {
            return $this->redirect('cabinet/index');
        }else{
            if (isset($_POST['LoginUserForm'])) {
                $model->attributes = Yii::$app->request->post('LoginUserForm');
                if ($model->validate()) {
                    Yii::$app->user->login($model->getUser($model->email));
                    return $this->redirect('cabinet/index');
                }
            }
            return $this->render('index',['model'=>$model]);
        }
    }
}
