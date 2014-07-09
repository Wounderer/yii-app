<?php
/**
 * @var $this SiteController
 * @var Vk $vk
 * @var $model VkUploadForm
 * @var $form CActiveForm
 */
$this->pageTitle = Yii::app()->name;
?>
<?php
if ($vk->hasToken()) {
    $user = $vk->usersGet();
    ?>
    Авторизован: <?= $user[0]['first_name'] . ' ' . $user[0]['last_name']; ?><br>
    <a href="/site/vkLogOut" class="btn btn-primary">Сбросить авторизацию</a>

    <?php
    CHtml::$errorMessageCss = 'text-danger';
    ?>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'upload-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'role' => 'form',
            'enctype' => 'multipart/form-data',
        ),
    )); ?>
    <h2>Загрузка фотографий Вконтакт</h2>
    <div class="form-group<?= $model->hasErrors('file[]') ? ' has-error' : ''; ?>">
        <?php echo $form->fileField($model, 'file[]', array('class' => 'form-control', 'multiple' => 'multiple', 'placeholder' => 'Логин',)); ?>
        <?php echo $form->error($model, 'file[]'); ?>
    </div>
    <?php echo CHtml::submitButton('Загрузить', array('class' => 'btn btn-primary')); ?>
    <?php if (isset($photos)) { ?>
        <h2>Загружено</h2>
        <?php foreach ($photos as $photo) { ?>
            <img src="<?= $photo['src']; ?>">
        <?php } ?>
    <?php } ?>
    <?php $this->endWidget(); ?>

<?php
} else {
    ?>
    <a href="<?= $vk->authGetLink(); ?>" class="btn btn-primary">Авторизоваться через Вконтакте</a>
<?php
}
?>
