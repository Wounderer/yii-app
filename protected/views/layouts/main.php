<?php /* @var $this Controller */

Yii::app()->getClientScript()->registerCoreScript('jquery');

Yii::app()->getClientScript()->registerCssFile('/bootstrap/css/bootstrap.min.css');
Yii::app()->getClientScript()->registerCssFile('/css/index.css');
//
Yii::app()->getClientScript()->registerScriptFile('/bootstrap/js/bootstrap.min.js', CClientScript::POS_END);
//Yii::app()->getClientScript()->registerScriptFile('/js/bootstrap/snippets/table.search.js', CClientScript::POS_END);
//Yii::app()->getClientScript()->registerScriptFile('/js/javascript.js', CClientScript::POS_END);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">PSFPRO</a>
        </div>
        <div class="collapse navbar-collapse">
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'nav navbar-nav'),
                'submenuHtmlOptions' => array('class' => 'dropdown-menu',),
                'encodeLabel' => false,
                'items' => array(
                    array('label' => 'Мои сообщения', 'url' => array('message/index'), 'visible' => !Yii::app()->user->isGuest),
                ),
            ));
            ?>
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'nav navbar-nav navbar-right'),
                'submenuHtmlOptions' => array('class' => 'dropdown-menu',),
                'encodeLabel' => false,
                'items' => array(
                    array('label' => 'Вход', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                    array('label' => 'Выход (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                ),
            ));
            ?>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container">
    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
            'htmlOptions' => array('class' => 'breadcrumb'),
        )); ?><!-- breadcrumbs -->
    <?php endif ?>

    <?php echo $content; ?>
</div>
<!-- /.container -->

</body>
</html>