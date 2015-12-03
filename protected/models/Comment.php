<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property string $author
 * @property string $email
 * @property string $url
 * @property integer $post_id
 *
 * The followings are the available model relations:
 * @property Post $post
 */
class Comment extends CActiveRecord
{
    const STATUS_PENDING=1;
    const STATUS_APPROVED=2;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{comment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content, author, email', 'required'),
            array('author, email, url', 'length', 'max'=>128),
            array('email','email'),
            array('url','url'),        
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
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
        );
    }

    public function getUrl($post=null)
    {
        if($post===null)
            $post=$this->post;
        return $post->url.'#c'.$this->id;
    }

    public function getAuthorLink()
    {
        if(!empty($this->url))
            return CHtml::link(CHtml::encode($this->author),$this->url);
        else
            return CHtml::encode($this->author);
    }

    public function getPendingCommentCount()
    {
        return $this->count('status='.self::STATUS_PENDING);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'content' => 'Comment',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'author' => 'Name',
            'email' => 'Email',
            'url' => 'Website',
            'post_id' => 'Post',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('author',$this->author,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('post_id',$this->post_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
                $this->create_time=time();
            return true;
        }
        else
            return false;
    }

    public function findRecentComments($limit=4)
        {
        return $this->with('post')->findAll(array(
            'condition'=>'t.status='.self::STATUS_APPROVED,
            'order'=>'t.create_time DESC',
            'limit'=>$limit,
        ));
    }

    public function approve()
    {
        $this->status=Comment::STATUS_APPROVED;
        $this->update(array('status'));
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
