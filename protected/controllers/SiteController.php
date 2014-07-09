<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $vk = new Vk();
        if ($this->getParam('code')) {
            $vk->authGetToken($this->getParam('code'));
            $this->redirect(Yii::app()->getBaseUrl(true));
        }

        $model = new VkUploadForm();
        $photosSaveResult = null;
        if ($this->getParam('VkUploadForm')) {
            if (!empty($_FILES['VkUploadForm']['tmp_name']['file'])) {
                $filesForUpload = [];
                foreach ($_FILES['VkUploadForm']['tmp_name']['file'] as $key => $file) {
                    $destination = Yii::app()->basePath . '/../photos/' . $_FILES['VkUploadForm']['name']['file'][$key];
                    move_uploaded_file($file, $destination);
                    $filesForUpload[] = realpath($destination);
                }

                $result = $vk->photosCreateAlbum('TEST');
                $uploadServer = $vk->photosGetUploadServer($result['aid']);
                $uploadResult = $vk->photosUpload($uploadServer['upload_url'], $filesForUpload);
                $photosSaveResult = $vk->photosSave($result['aid'], $uploadResult['server'], $uploadResult['photos_list'], $uploadResult['hash']);
            }
        }

        $this->render('index', [
            'vk' => $vk,
            'model' => $model,
            'photos' => $photosSaveResult,
        ]);
    }

    public function actionVkLogOut()
    {
        $vk = new Vk();
        $vk->clearToken();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}