<?php

/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property string $id
 * @property string $text
 * @property string $created
 * @property string $receiver_id
 * @property string $sender_id
 * @property integer $isSeen
 *
 * The followings are the available model relations:
 * @property User $receiver
 * @property User $sender
 */
class Message extends Model
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('isSeen, sender_id, receiver_id', 'required'),
            array('receiver_id, sender_id', 'length', 'max' => 11),
            array('text, ', 'filter', 'filter' => array($this, 'purifyFilter')),
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
            'receiver' => array(self::BELONGS_TO, 'User', 'receiver_id'),
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'text' => 'Text',
            'created' => 'Created',
            'receiver_id' => 'Receiver',
            'sender_id' => 'Sender',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Message the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param $text
     * @return string
     */
    public function purifyFilter($text)
    {
        $purifiedText = self::purify($text);

        if ($purifiedText) {
            return $purifiedText;
        }

        return '';
    }

    /**
     * @param $text
     * @param array $options
     * @return mixed
     */
    static public function purify($text, array $options = array())
    {
        $defaultOptions = array(
            'URI.AllowedSchemes' => array(
                'http' => true,
            ),
            'AutoFormat.RemoveEmpty' => true,
            'Attr.AllowedFrameTargets' => array('_blank'),
            'Core.HiddenElements' => array(
                'style' => true,
            ),
            'HTML.SafeObject' => true,
            'HTML.SafeIframe' => true,
            'Output.FlashCompat' => true,
            'URI.SafeIframeRegexp' => '%^http://(www.youtube.com/embed/|player.vimeo.com/video/)%',
            'HTML.ForbiddenElements' => array(
                'title', 'html', 'head', 'body', 'script'
            ),
        );

        $purifier = new CHtmlPurifier();

        if ($options) {
            $purifier->setOptions(array_replace_recursive($defaultOptions, $options));
        }

        return $purifier->purify($text);
    }
}
