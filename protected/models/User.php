<?php

/**
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Message[] $toMessages
 * @property Message[] $fromMessages
 */
class User extends Model
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('login, password, name', 'required'),
            array('password, login, name', 'length', 'max' => 255),
            array('login', 'unique', 'message' => 'Такой логин уже существует'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'toMessages' => array(self::HAS_MANY, 'Message', 'receiver_id'),
            'fromMessages' => array(self::HAS_MANY, 'Message', 'sender_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'name' => 'Имя',
            'created' => 'Created',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
