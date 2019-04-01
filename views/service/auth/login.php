<?php

use app\models\form\LoginForm;
use yii\bootstrap\ActiveForm;
use app\helpers\MainHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>",
];
$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>",
];
$this->title = MainHelper::getAttributeLabel('login');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
            <?= $form->field($model, 'username', $fieldOptions1)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                ->label(false) ?>
            <?= $form->field($model, 'password', $fieldOptions2)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                ->label(false) ?>
            <?= Html::submitButton(
                MainHelper::getAttributeLabel('login'),
                ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']
            ) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
