<?php

class MessageController extends Controller
{
    /**
     * accessRules
     *
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow', 'users' => array('@')),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionIndex()
    {
        // Get all users except active user
        $users = User::model()->findAll('id<>:id', array(':id' => Yii::app()->user->id));
        $this->render('index', [
            'users' => $users,
        ]);
    }

    public function actionSendMessage()
    {
        // Check parameter
        if (!$receiver = $this->getParam('receiver')) {
            throw new CHttpException(400, 'Не передан идентификатор собеседника');
        }

        // Check parameter
        if (!$messageText = $this->getParam('text')) {
            throw new CHttpException(400, 'Не передан текст сообщения или в сообщении пусто');
        }

        // Check if suck a user exists
        if (!User::model()->findAllByPk($receiver)) {
            throw new CHttpException(400, 'Не найден пользователь с идентификатором ' . $receiver);
        }

        // Create new message
        $message = new Message();
        $message->text = $messageText;
        $message->receiver_id = $receiver;
        $message->sender_id = Yii::app()->user->id;
        $message->created = date('y-m-d H:i:s');
        $message->save();

        $this->renderPartial('sendMessage');
    }


    public function actionGetMessages()
    {
        // Check parameter
        if (!$sender = $this->getParam('sender')) {
            throw new CHttpException(400, 'Не передан идентификатор собеседника');
        }

        // Check user exists
        if (!User::model()->findAllByPk($sender)) {
            throw new CHttpException(400, 'Не найден пользователь с идентификатором ' . $sender);
        }

        // Get messages thread orderd by date
        $messages = Message::model()->findAll(array(
            'condition' => '((receiver_id=:userId AND sender_id=:sender) OR (sender_id=:userId AND receiver_id=:sender)) AND id > :messageId',
            'params' => array(
                ':userId' => Yii::app()->user->id,
                ':sender' => $sender,
                ':messageId' => $this->getParam('messageId'),
            ),
            'order' => 'created',
        ));

        // Set messages isSeen flag to true
        $arr = array();
        foreach ($messages as $message) {
            if (Yii::app()->user->id == $message->receiver_id) {
                $message->isSeen = 1;
                $message->save();
            }
            $arr[$message->id] = $message->attributes;
        }


        $this->renderPartial('getMessages', array(
            'messages' => $arr,
            'sender' => $sender,
        ));
    }

    public function actionCountNewMessages()
    {
        $newMessages = Message::model()->findAll(array(
            'condition' => 'receiver_id=:userId AND isSeen=0',
            'params' => array(':userId' => Yii::app()->user->id)
        ));
        $result = [];
        $result['all'] = count($newMessages);
        foreach ($newMessages as $message) {
            $result[$message->sender_id] = $result[$message->sender_id] + 1;
        }

        $this->renderPartial('countNewMessages', array(
            'counter' => $result,
        ));
    }
} 