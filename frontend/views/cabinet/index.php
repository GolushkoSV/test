<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.08.18
 * Time: 13:38
 */
?>

<?php use yii\widgets\LinkPager; ?>

<div class="container">
    <header>
        <div class="row">
            <div class="col-lg-6">
                <div>
                    <h2>Личный кабинет</h2>
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
                    <h2>Ваш баланс составляет: <?= number_format(Yii::$app->user->identity->score, 2, '.', ' ') ?> р</h2>
                </ul>
            </div>
        </div>
        <div class="col-lg-4">

            <div class="logout">
                <a href="../cabinet/logout">Выйти</a>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
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
                    <li>Почтовый адрес<span>|</span>Сумма<span>|</span>№ Счёта<span>|</span>Дата<span>|</span>Направление платежа<span>|</span>Баланс</li>

                    <?php if ($payments == NULL){
                        echo "<h3>Платежи ещё не совершались</h3>";
                    }else {
                        foreach ($payments as $payment) {

                            echo "<li>" . $payment['email'] . "<span>  |  </span>" . number_format($payment['score'], 2, '.', ' ') . ", р <span>  |  </span>№ " . $payment['number_account'] . " <span>  |  </span>" . $payment['date'] . "<span>  |  </span>" . $payment['directing'] . "<span>  |  </span>" . number_format($payment['balance'], 2, '.', ' ') . ", р</li>";

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

</div>