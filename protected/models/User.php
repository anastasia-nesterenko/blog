<?php

class User extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password, email', 'required'),
            array('username, email', 'length', 'max'=>128),
            array('password', 'length', 'max'=>64),
            array('profile', 'safe'),
            array('id, username, password, email, profile', 'safe', 
                'on'=>'search'),
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
            'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'profile' => 'Profile',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('profile',$this->profile,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function validatePassword($password)
        {
            return CPasswordHelper::verifyPassword($password,$this->password);
    }
 
       public function hashPassword($password)
    {
            return CPasswordHelper::hashPassword($password);
    }
}
