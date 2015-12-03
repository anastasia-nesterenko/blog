<?php

class CommentController extends Controller
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
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('admin','delete'),
                'users'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function actionCreate()
    {
        $model=new Comment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Comment']))
        {
            $model->attributes=$_POST['Comment'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Comment']))
        {
            $model->attributes=$_POST['Comment'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    public function actionApprove()
    {
        if(Yii::app()->request->isPostRequest)
        {
            $comment=$this->loadModel();
            $comment->approve();
            $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request...');
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
        $dataProvider=new CActiveDataProvider('Comment', array(
            'criteria'=>array(
            'with'=>'post',
            'order'=>'t.status, t.create_time DESC',
            ),
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
        $model=new Comment('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Comment']))
            $model->attributes=$_GET['Comment'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model=Comment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Comment $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
