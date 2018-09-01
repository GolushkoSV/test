<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.08.18
 * Time: 21:43
 */
?>


<?php use yii\widgets\LinkPager; ?>


<div class="container">
    <header>
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <h2>Панель Администратора</h2>

                </div>
            </div>
            <div class="col-lg-6">
                <h3><?= Yii::$app->user->identity->email; ?></h3>
            </div>
        </div>
        <div class="line"></div>
    </header>
    <div class="row">
        <div class="col-lg-8">
            <div class="my-nav">
                <ul>
                    <li><a href="index.php?r=cabinet/index" >Контрагенты</a></li>
                    <li><a href="index.php?r=cabinet/procedure" class="active-nav">Операции</a></li>
                    <div id="upload-payments">
                        <a  class="btn btn-default">Загрузить файл "Excel" c платежами</a>
                    </div>
                </ul>
            </div>
        </div>
        <div class="col-lg-4">

            <div class="logout">
                <a href="index.php?r=cabinet/logout">Выйти</a>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-lg-6">
            <div class="sort">
                <h4>Сортировать по:</h4>
                <?= $sort->link('date') ?>
                <?= $sort->link('score') ?>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="list-agents">
                <ul>
                    <li>Почтовый адрес<span>|</span>Сумма<span>|</span>№ Счёта<span>|</span>Дата<span>|</span>Направление платежа<span>|</span>Баланс контрагента</li>
                    </li>
                    <?php if ($data == NULL){
                        echo "<h3>Платежи ещё не совершались</h3>";
                    }else {
                        foreach ($data as $item) {

                            echo "<li>" . $item['email'] . "<span>  |  </span>" . number_format($item['score'], 2, '.', ' ') . ", р <span>  |  </span>№ " . $item['number_account'] . " <span>  |  </span>" . $item['date'] . "<span>  |  </span>" . $item['directing'] . "<span>  |  </span>" . number_format($item['balance'], 2, '.', ' ') . ", р</li>";

                        }
                    }?>
                </ul>
            </div>
        </div>
    </div>


<?php echo LinkPager::widget([
    'pagination' => $pages,
    'registerLinkTags' => true
]); ?>





<!--Прелоадер - начало -->
<div class="wrap_for_preloader">

    <div class="cssload-loader">
        <div class="cssload-inner cssload-one"></div>
        <div class="cssload-inner cssload-two"></div>
        <div class="cssload-inner cssload-three"></div>
    </div>

</div>
<!--Прелоадер - конец -->



</div>