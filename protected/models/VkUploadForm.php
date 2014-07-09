<?php

class VkUploadForm extends CFormModel
{
    public $file;

    public function rules()
    {
        return array(
            array('file[]', 'file', 'types' => 'jpg, jpeg, gif, png', 'maxSize' => 10 * 1024 * 1024),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'file' => 'Загрузка файла',
        );
    }
}