<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */
$this->pageTitle = Yii::app()->name . ' - Вход на сайт';
$this->breadcrumbs = array(
    'Вход на сайт',
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
    ),
)); ?>
<h2>Вход на сайт</h2>
<div class="form-group<?= $model->hasErrors('username') ? ' has-error' : ''; ?>">
    <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => 'Логин',)); ?>
    <?php echo $form->error($model, 'username'); ?>
</div>
<div class="form-group<?= $model->hasErrors('password') ? ' has-error' : ''; ?>">
    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Пароль',)); ?>
    <?php echo $form->error($model, 'password'); ?>
</div>
<div class="checkbox">
    <label>
        <?php echo $form->checkBox($model, 'rememberMe'); ?> <?php echo $model->getAttributeLabel('rememberMe'); ?>
    </label>
</div>
<?php echo CHtml::submitButton('Войти', array('class' => 'btn btn-lg btn-primary btn-block')); ?>
<?php $this->endWidget(); ?>
