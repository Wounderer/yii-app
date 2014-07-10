<?php

/**
 * Model
 *
 * @uses CActiveRecord
 * @author Pavel Aksyonov <pavela@mte-telecom.ru>
 *
 * @property integer $id
 * @property integer $updated
 * @property integer $created
 * @property integer $user_id
 * @property integer $deleted
 *
 */
class Model extends CActiveRecord
{

    public $htmlPurifier;

    /**
     * @return bool
     */
    public function beforeSave()
    {
        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if (array_key_exists('updated', $this->attributes)) {
            $this->updated = date('Y-m-d H:i:s');
        }

        return parent::beforeValidate();
    }

    /**
     *
     */
    public function afterValidate()
    {
        return parent::afterValidate();
    }

    /**
     * getModelName
     *
     * @return string
     */
    public function getModelName()
    {
        return ucfirst(get_class($this));
    }

}
