<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.08.18
 * Time: 13:34
 */

namespace frontend\controllers;

use common\models\Payment;
use yii\data\Sort;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;

class CabinetController extends Controller
{

    public $layout = 'basic';

    public function actionIndex(){

        $this->view->title = 'Личный кабинет';
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else{

            $sort = new Sort(['attributes'=>[
                'score'=>[
                    'default' => SORT_DESC,
                    'label' => 'Сумма',
                ],
                'date'=>[
                    'default' => SORT_DESC,
                    'label' => 'Дата',
                ],
            ]]);

            $data = Payment::find()->where(['email'=>Yii::$app->user->identity->email]);
            //Подключаем класс пагинации. pageSize задаём кол-во записей на странице
            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 3]);
            //Работаем с урлом
            $pages->pageSizeParam = false;
            $payments = $data->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy($sort->orders)
                ->all();

            return $this->render('index',['payments'=>$payments, 'pages'=>$pages, 'sort'=>$sort]);
        }
    }


    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect('/');
    }
}