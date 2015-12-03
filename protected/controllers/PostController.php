<?php

class PostController extends Controller
{
    public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow',
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionView()
    {
        $post=$this->loadModel();
        $comment=$this->newComment($post); 
        $this->render('view',array(
            'model'=>$post,
            'comment'=>$comment,
        ));
    }

    public function actionCreate()
    {
        $model=new Post;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    protected function newComment($post)
    {
        $comment=new Comment;
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
        {
            echo CActiveForm::validate($comment);
            Yii::app()->end();
        }

        if(isset($_POST['Comment']))
        {
            $comment->attributes=$_POST['Comment'];
            if($post->addComment($comment))
            {
                if($comment->status==Comment::STATUS_PENDING)
                    Yii::app()->user->setFlash('commentSubmitted',
                        'Thank you for your comment.
                         Your comment will be posted once it is approved.');
                $this->refresh();
            }
        }
        return $comment;
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();


        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria=new CDbCriteria(array(
            'condition'=>'status='.Post::STATUS_PUBLISHED,
            'order'=>'update_time DESC',
            'with'=>'commentCount',
        ));
        if(isset($_GET['tag']))
            $criteria->addSearchCondition('tags',$_GET['tag']);
        $dataProvider=new CActiveDataProvider('Post', array(
            'pagination'=>array('pageSize'=>10,    ),
            'criteria'=>$criteria,
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Post('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Post']))
            $model->attributes=$_GET['Post'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    private $_model;
 
    public function loadModel()
    {
        if($this->_model===null)
        {
            if(isset($_GET['id']))
            {
                if(Yii::app()->user->isGuest)
                    $condition='status='.Post::STATUS_PUBLISHED
                        .' OR status='.Post::STATUS_ARCHIVED;
                else
                    $condition='';
                $this->_model=Post::model()->findByPk($_GET['id'], $condition);
            }
            if($this->_model===null)
                throw new CException('Post number '.$_GET['id'].' not found');
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param Post $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
