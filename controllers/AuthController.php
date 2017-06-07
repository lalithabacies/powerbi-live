<?php

namespace app\controllers;

use Yii;
use app\models\Workspace;
use app\models\Collection;
use app\models\Dataset;
use app\models\User;
use yii\web\UploadedFile;

class AuthController extends \yii\web\Controller
{
	
	public $modelClass = 'app\models\User';
	
	
	public function actionIndex(){
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$id="11";
		$model = User::find()->where(["id"=>$id])->one();
	
		echo "<pre>";;
		
		//print_r(json_encode($model));
		
		return array('status'=>true,'data'=>$model);
		
	}

	
	public function actionCreate(){
		
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model = new User();
		$model->attributes = \Yii::$app->request->post();
		echo "<pre>";;
		
		//print_r(json_encode($model));
		
		return array('status'=>true,'data'=>$model);
		
	}

	


}