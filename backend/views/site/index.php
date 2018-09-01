<?php

/* @var $this yii\web\View */

use \yii\widgets\ActiveForm;


?>

<div class="wrap_for_logo">
    <div class="logo">Berdges</div>
</div>
<h3 class="text-admin">Администратор</h3>

<div class="form_for_user">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email')->textInput( ['placeholder' => " Your Email"])->label(""); ?>
    <?= $form->field($model, 'password')->passwordInput( ['placeholder' => " Your Password"])->label(""); ?>
    <div class="wrap_for_button">
        <button class="btn btn-primary">Войти</button>
    </div>

    <?php ActiveForm::end(); ?>
</div>

