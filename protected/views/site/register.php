<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */
$this->pageTitle = Yii::app()->name . ' - Регистрация';
$this->breadcrumbs = array(
    'Регистрация',
);
CHtml::$errorMessageCss = 'text-danger';
?>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array(
        'role' => 'form',
        'class' => 'form-signin',
    ),
)); ?>
<h2>Регистрация</h2>
<div class="form-group<?= $model->hasErrors('name') ? ' has-error' : ''; ?>">
    <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => 'Имя',)); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>
<div class="form-group<?= $model->hasErrors('login') ? ' has-error' : ''; ?>">
    <?php echo $form->textField($model, 'login', array('class' => 'form-control', 'placeholder' => 'Логин',)); ?>
    <?php echo $form->error($model, 'login'); ?>
</div>
<div class="form-group<?= $model->hasErrors('password') ? ' has-error' : ''; ?>">
    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Пароль',)); ?>
    <?php echo $form->error($model, 'password'); ?>
</div>
<?php echo CHtml::submitButton('Зарегистрироваться', array('class' => 'btn btn-lg btn-primary btn-block')); ?>
<?php $this->endWidget(); ?>
