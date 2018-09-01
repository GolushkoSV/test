<?php

/* @var $this yii\web\View */
use yii\widgets\ActiveForm;

?>


<div class="wrap_for_logo">
    <div class="logo">Berdges</div>
</div>


<div class="form_for_user">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput( ['placeholder' => " Your Email"])->label(""); ?>
    <?= $form->field($model, 'password')->passwordInput( ['placeholder' => " Your Password"])->label(""); ?>

    <div class="wrap_for_button">
        <button class="btn btn-success">Авторизироваться</button>
    </div>

    <?php ActiveForm::end() ?>
</div>


