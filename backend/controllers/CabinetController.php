<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.08.18
 * Time: 13:54
 */

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use PHPExcel_IOFactory;
use common\models\User;
use common\models\Payment;
use yii\data\Pagination;
use yii\data\Sort;
use yii\swiftmailer\Mailer;
use common\models\Bank;


class CabinetController extends Controller
{
    public $layout = 'basic';

    public function actionIndex()
    {
        Yii::$app->view->title="Панель администратора";

        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else {
            //Сортировка
            $sort = new Sort([
                'attributes' => [
                    'email' => [
                        'default' => SORT_DESC,
                        'label' => 'Email',
                    ],
                    'score' => [
                        'default' => SORT_DESC,
                        'label' => 'Баланс',
                    ]
                ],
            ]);

            //Постраничная навигация контента
            $data = User::find()->where(['type' => 'agent']);
            //Подключаем класс пагинации. pageSize задаём кол-во записей на странице
            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 8]);
            //Работаем с урлом
            $pages->pageSizeParam = false;
            $users = $data->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy($sort->orders)
                ->all();

            return $this->render('index',['agents'=>$users, 'pages'=>$pages,'sort'=>$sort]);
        }
    }

    public function actionProcedure(){
        Yii::$app->view->title = 'Панель Администратора';
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }else {

            //Сортировка
            $sort = new Sort([
                'attributes' => [
                    'score' => [
                        'default' => SORT_DESC,
                        'label' => 'Сумма',
                    ],
                    'date' => [
                        'default' => SORT_DESC,
                        'label' => 'Дата',
                    ]
                ],
            ]);

            //Постраничная навигация контента
            $data = Payment::find();
            //Подключаем класс пагинации. pageSize задаём кол-во записей на странице
            $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 8]);
            //Работаем с урлом
            $pages->pageSizeParam = false;
            $models = $data->offset($pages->offset)
                ->limit($pages->limit)
                ->orderBy($sort->orders)
                ->all();

            return $this->render('procedure', ['data' => $models,'pages' => $pages,'sort'=>$sort]);
        }
    }


    public function actionUpload()
    {

        $objPHPExcel = PHPExcel_IOFactory::load('../../data/data.xls');
        $array = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

        for ($i = 2; $i <= sizeof($array); $i++) {
            if (!Payment::findOne([
                'email' => $array[$i]['A'],
                'score' => $array[$i]['B'],
                'number_account' => $array[$i]['C'],
                'date' => $array[$i]['D'],
                'directing' => $array[$i]['E'],
            ])){
                //Если номер счёта не существует и сумма вносится, если баланс контрагента больше,чем сумма, которую он хочет внести, выполняем запись.
                if (!$bank = Bank::findOne(['account_number'=>$array[$i]['C']])){
                    if($array[$i]['E'] == 'up' && $array[$i]['F'] >= $array[$i]['B']){
                        $bank = new Bank();
                        $bank->account_number = $array[$i]['C'];
                        $bank->balance = $array[$i]['B'];
                        $bank->save();

                        $new_data = new Payment();
                        $new_data->email = $array[$i]['A'];
                        $new_data->score = $array[$i]['B'];
                        $new_data->number_account = $array[$i]['C'];
                        $new_data->date = $array[$i]['D'];
                        $new_data->directing = $array[$i]['E'];

                        //Если пользователя нет в базе данных, выполняем его запись
                        $user = User::findOne(['email'=> $array[$i]['A']]);
                        if (!$user) {
                            $new_user = new User();
                            $new_user->email = $array[$i]['A'];
                            $new_user->type = 'agent';
                            $new_user->score = $array[$i]['F'] - $array[$i]['B'];
                            //Пароль для отправки его по почте пользователю.
                            $password = $new_user->generatePassword(10);
                            //Пароль, который внесётся в базу данных.
                            $new_user->password = sha1(substr($password, -3, 3) . mb_substr($password, 3, 4) . substr($password, 0, 3));
                            $new_user->save();

                            $new_data->balance = $array[$i]['F'];
                            $new_data->save();

                            Yii::$app->mailer->compose()
                                ->setFrom('serega18-11gol@mail.ru')
                                ->setTo($array[$i]['A'])
                                ->setSubject('Freematiq')
                                ->setTextBody('Поздравляем, теперь вы можете посетить наш сервис!')
                                ->setHtmlBody("Ваш логин: ".$array[$i]['A']."<br>Ваш пароль: ".$password."<br>")
                                ->send();
                        }else{
                            $new_data->balance = $user['score'];
                            $new_data->save();
                            $user['score'] = $array[$i]['F'] - $array[$i]['B'];
                            $user->save();
                        }
                    }

                    //Если номер счёта существует и сумма выводится со счёта , если баланс банка больше,чем сумма, которую контрагент хочет вывести, выполняем запись.
                }else if($bank['balance'] >= $array[$i]['B'] && $array[$i]['E'] == 'down'){
                    $bank['balance'] = $bank['balance'] - $array[$i]['B'];
                    $bank->save();

                    $new_data = new Payment();
                    $new_data->email = $array[$i]['A'];
                    $new_data->score = $array[$i]['B'];
                    $new_data->number_account = $array[$i]['C'];
                    $new_data->date = $array[$i]['D'];
                    $new_data->directing = $array[$i]['E'];


                    //Если пользователя нет в базе данных, выполняем его запись.
                    $user = User::findOne(['email'=> $array[$i]['A']]);
                    if (!$user) {
                        $new_user = new User();
                        $new_user->email = $array[$i]['A'];
                        $new_user->type = 'agent';
                        $new_user->score = $array[$i]['B'];
                        //Пароль для отправки его по почте пользователю.
                        $password = $new_user->generatePassword(10);
                        //Пароль, который внесётся в базу данных.
                        $new_user->password = sha1(substr($password, -3, 3) . mb_substr($password, 3, 4) . substr($password, 0, 3));
                        $new_user->save();

                        $new_data->balance = $array[$i]['F'];
                        $new_data->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('serega18-11gol@mail.ru')
                            ->setTo($array[$i]['A'])
                            ->setSubject('Freematiq')
                            ->setTextBody('Поздравляем, теперь вы можете посетить наш сервис!')
                            ->setHtmlBody("Ваш логин: ".$array[$i]['A']."<br>Ваш пароль: ".$password."<br>")
                            ->send();
                    }else{
                        $new_data->balance = $user['score'];
                        $new_data->save();
                        $user['score'] = $user['score'] + $array[$i]['B'];
                        $user->save();
                    }

                    //Если номер счёта существует и сумма вносится, если баланс контагента больше,чем сумма, которую он хочет внести выполняем запись
                }else if($array[$i]['E'] == 'up' && $array[$i]['F'] >= $array[$i]['B']){
                    $bank['balance'] = $bank['balance'] + $array[$i]['B'];
                    $bank->save();

                    $new_data = new Payment();
                    $new_data->email = $array[$i]['A'];
                    $new_data->score = $array[$i]['B'];
                    $new_data->number_account = $array[$i]['C'];
                    $new_data->date = $array[$i]['D'];
                    $new_data->directing = $array[$i]['E'];


                    //Если пользователя нет в базе данных, выполняем его запись.
                    $user = User::findOne(['email'=> $array[$i]['A']]);
                    if (!$user) {
                        $new_user = new User();
                        $new_user->email = $array[$i]['A'];
                        $new_user->type = 'agent';
                        $new_user->score = $array[$i]['F'] - $array[$i]['B'];
                        //Пароль для отправки его по почте пользователю.
                        $password = $new_user->generatePassword(10);
                        //Пароль, который внесётся в базу данных.
                        $new_user->password = sha1(substr($password, -3, 3) . mb_substr($password, 3, 4) . substr($password, 0, 3));
                        $new_user->save();

                        $new_data->balance = $array[$i]['F'];
                        $new_data->save();

                        Yii::$app->mailer->compose()
                            ->setFrom('serega18-11gol@mail.ru')
                            ->setTo($array[$i]['A'])
                            ->setSubject('Freematiq')
                            ->setTextBody('Поздравляем, теперь вы можете посетить наш сервис!')
                            ->setHtmlBody("Ваш логин: ".$array[$i]['A']."<br>Ваш пароль: ".$password."<br>")
                            ->send();
                    }else{
                        $new_data->balance = $user['score'];
                        $new_data->save();
                        $user['score'] = $array[$i]['F'] - $array[$i]['B'];
                        $user->save();
                    }
                }
            }
        }

        return $this->redirect('index.php?r=cabinet/procedure');
    }

    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect('/admin');
    }
}