<?php

namespace app\controllers;

use Yii;
use app\models\Dashboard;
use app\models\search\Dashboard as DashboardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Workspace;
use app\models\Collection;
use app\models\DataModel;
use yii\web\UploadedFile;
use app\models\Reports;
use yii\helpers\Html;
/**
 * DashboardController implements the CRUD actions for Dashboard model.
 */
class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Dashboard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DashboardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		isset($_REQUEST['workspace_id'])?$dataProvider->query->andFilterWhere(['workspace_id'=>$_REQUEST['workspace_id']]):'';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dashboard model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model 		= $this->findModel($id);
		$workspace	= Workspace::find()->where(['w_id'=>$model->workspace_id])->one();
		$collection = Collection::find()->where(['collection_id'=>$workspace->collection_id])->one();
		$reports	= Reports::find()->where(['workspace_id'=>$workspace->w_id,'dataset_id'=>$model->dataset_id])->one();
        return $this->render('view', [
            'model' 	=> $model,
			'collection'=> $collection,
			'reports'	=> $reports,
        ]);
    }

    /**
     * Creates a new Dashboard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        set_time_limit(0);
		$model = new Dashboard();
		$collections	= Collection::find()->all();
		$workspaces		= Workspace::find()->all();
		
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
			$model->file = UploadedFile::getInstance($model, 'file');
			if ($model->upload() ) {
                // file is uploaded successfully
				$data = \app\components\PBI_Excel::import(\Yii::$app->basePath."/web/uploads/". $model->file->baseName . '.' . $model->file->extension, [
					'setFirstRecordAsKeys' => true, 
					'setIndexSheetByName' => true, 
				]);
				//To remove empty sheets
				$data=array_filter(array_map('array_filter', $data));
				//print_r($data);die;
				$tables = [];
				
				//**Naming convention check starts

 				$checkTableName = $this->validateNamingConvention($data,$model);		
				if ($checkTableName['status']=='error'){
					$model->addError("file",$checkTableName['msg']);
					return $this->render('create', [
					'model' => $model,
					'collections' => $collections,
					'workspaces' => $workspaces
					]);
				} 
				//**Check Ends..
				
				$data=array_filter(array_map('array_filter', $data));
				foreach($data as $key=>$sheet){
					$datamodel = new DataModel();
					$datamodel->model_name = $model->prefix."_".$key;					
					$tables[] = $datamodel->model_name;
/* 					if(!isset($sheet[0])){
						$model->addError("file","Excel file requires atleast one sheet.");
						return $this->render('create', [
							'model' => $model,
							'collections' => $collections,
							'workspaces' => $workspaces
						]); 
					} */
					$headers = $sheet[0];
					$attributes = [];
					foreach($headers as $header=>$value){
						if($header!=''){
							if((strtolower($header) == 'id'))
								$attributes[] = ['field_name'=>$header,'field_type'=>'integer'];
							else $attributes[] = ['field_name'=>$header,'field_type'=>'text'];							
						}
					}					
					$datamodel->attributes = serialize($attributes);					
					if($datamodel->save()){
						
						/* Comment out to save the data as well. 
						 * Currently disabled to allow data only from wp
						 */
						
						/*
						foreach($sheet as $header=>$data){
							foreach($data as $key=>$d){
								//eliminate the null keys
								if($key == '')
									unset($data[$key]);
							}
							$data['eq_customer_id'] = \Yii::$app->user->id;
							\Yii::$app->db->createCommand()
								->insert($datamodel->model_name, $data)->execute();
						} 
						*/
					}
					$model->models = serialize($tables);
					$model->save();
				}
				return $this->redirect(['view', 'id' => $model->dashboard_id]);
            }						
            
        } else {
            return $this->render('create', [
                'model' => $model,
				'collections' => $collections,
				'workspaces' => $workspaces
            ]);
        }
    }
	

	
	/**
	*
	* Uploading the pbix file
	*/
	
	public function actionAddpbix($id,$change='')
	{
		
		$dashboard		= $this->findModel($id);
		//$dashboard		= new Dashboard();
		$collections	= Collection::find()->all();
		$workspaces		= Workspace::find()->all();
                
		if($dashboard->load(Yii::$app->request->post())){
			
            $workspace	 	= Workspace::findOne($dashboard->workspace_id);
			$collection 	= Collection::findOne($workspace->collection_id);
			$uploadedFile   = UploadedFile::getInstance($dashboard, 'file');
			
			//Saving the file to local directory for cURL access.
			$uploadedFile->saveAs('uploads/'.$uploadedFile->name);
			$rand=($change==1)?'_'.rand(1,100):'';
			//request URL which returns dataset id.
			$end_url		='https://api.powerbi.com/v1.0/collections/';
            $end_url        .= $collection->collection_name;
            $end_url        .='/workspaces/'.$workspace->workspace_id.'/imports?datasetDisplayName='.urlencode($dashboard->dashboard_name.$rand);
			$access_key		= $collection->AppKey;
			
			//create file which can access via cURL.
			$curl_file = curl_file_create(\Yii::$app->basePath.'/web/uploads/'.$uploadedFile->name,'pbix',$uploadedFile->baseName);
			$params = ['file' => $curl_file];
            $response	= json_decode($workspace->doCurl_POST($end_url,$access_key,$params,"multipart/form-data","POST"));
			//print_r($response);die;
			if(isset($response->error->message)){
				//flash error message
				Yii::$app->session->setFlash('some_error',  $response->error->message);
				return $this->render('create-dataset',[
					'model'=>$dataset,
					'workspaces' => $workspaces,
				]);
			}
			//$dashboard->dataset_id 	= $response->id;
			$dashboard->workspace_id	= $workspace->w_id;
			
			//The request URL which returns the dataset id of the workspace
			//if use above dataset_id the datasource response is Datasource ID missing.We are the below dataset for the next request.
			$url = 'https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/datasets';
			$respns_dtast = json_decode($workspace->doCurl_GET($url,$access_key));
			if(isset($respns_dtast->error->message)){
				//flash error message
				Yii::$app->session->setFlash('some_error',  $respns_dtast->error->message);
				return $this->render('create-dataset',[
					'model'=>$dataset,
					'workspaces' => $workspaces,
				]);
			}
			foreach($respns_dtast->value as $datasets)
			{
				$dashboard->dataset_id 	= $datasets->id;
				//Returns the datasource id,gateway id
				$end_url ='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/datasets/'.$datasets->id.'/Default.GetBoundGatewayDatasources';


				$respns_ds_gw = json_decode($workspace->doCurl_GET($end_url,$access_key));
				if(isset($respns_ds_gw->error->message)){
					//flash error message
					Yii::$app->session->setFlash('some_error',  $respns_ds_gw->error->message);
					return $this->render('create-dataset',[
						'model'=>$dataset,
						'workspaces' => $workspaces,
					]);
				}
				if(isset($respns_ds_gw->value))
				{
				foreach($respns_ds_gw->value as $gateway)
				{
				$dashboard->datasource_id 	= $gateway->id;
				$dashboard->gateway_id 	= $gateway->gatewayId; 
				$dashboard->pbix_file	 	= 'uploads/'.$uploadedFile->name;
				
				//report generation with collection id and workspace id
				$url="https://api.powerbi.com/v1.0/collections/".$collection->collection_name."/workspaces/".$workspace->workspace_id."/reports";

				$response = json_decode($workspace->doCurl_GET($url,$access_key));
				foreach($response->value as $res){
					if($res->datasetId == $datasets->id){
					$reports  	= new Reports();
					$reports->report_name 	= $res->name;
					$reports->report_guid 	= $res->id;
					$reports->web_url		= $res->webUrl;
					$reports->embed_url		= $res->embedUrl;
					$reports->dataset_id	= $res->datasetId;
					$reports->workspace_id	= $workspace->w_id;
					$reports->save(false);
					}
				}
				$dashboard->report_id 	= $reports->r_id;
				
				
				//PATCH
				$patchurl="https://api.powerbi.com/v1.0/collections/".$collection->collection_name."/workspaces/".$workspace->workspace_id."/gateways/".$gateway->gatewayId."/datasources/".$dashboard->datasource_id;
				$params = json_encode([
				"credentialType"=>"Basic",
					"basicCredentials"=>[
					"username"=>"eqvision",
					"password"=>"Al@inno17!",
					]
				]);
				$respns_patch = json_decode($workspace->doCurl_POST($patchurl,$access_key,$params,"application/json","PATCH"));
				$dashboard->save(false);
				}
				}
			
			}
			
			return $this->redirect(['dashboard/index']);

		}
		else
		{
			return $this->render('addpbix',[
				'model'			=> $dashboard,
                'workspaces' 	=> $workspaces,
				'collections'	=> $collections,
			]);
		}
	}

	public function actionEditForm($id){
		$model = $this->findModel($id);
		if($post = \Yii::$app->request->post()){
			//handle post data
			//print_r($post);die;
			$model->form_data = serialize($post['tables']);
			if($model->save(false))
				$this->redirect(['edit-form','id'=>$id]);
		}else
		return 	$this->render('form_editor', [
                'model' => $model
            ]);		
	}
	
	public function actionCreateForm($id){
		$model = $this->findModel($id);
		if($model->form_data!='')
			$this->redirect(['edit-form','id'=>$id]);
		if(isset($model->models))
		{
		$tablenames = unserialize($model->models);
		$tables = [];
		foreach($tablenames as $tablename){
			//$tableSchema = \Yii::$app->db->getTableSchema($tablename);
			//foreach ($tableSchema->columns as $column) {				
			$datamodel = DataModel::find()->where(['model_name'=>$tablename])->one();
			$tables[$tablename]['attributes'] = unserialize($datamodel->attributes);
			$tables[$tablename]['form_data'] = unserialize($datamodel->form_data);
		}
		}
		if($post = \Yii::$app->request->post()){
			//handle post data
			//print_r($post);die;
			$model->form_data = serialize($post['tables']);
			if($model->save(false))
			 return $this->redirect(['edit-form','id'=>$id]);
		}
		else 
		return 	$this->render('form_generator', [
                'model' => $model,
				'tables' => isset($tables)?($tables):'',
            ]);
	}

    /**
     * Updates an existing Dashboard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dashboard_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dashboard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$dashboard	= $this->findModel($id);
		$reports	= Reports::findOne(['r_id'=>$dashboard->report_id]);
		//$dataset 	= Dataset::findOne(['dataset_id'=>$reports->dataset_id]);
		$workspace	= Workspace::find()->where(['w_id'=>$reports->workspace_id])->one();
		$collection	= Collection::find()->where(['collection_id'=>$workspace->collection_id])->one();
		
		//Dataset deletion
		$url='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/datasets/'.$reports->dataset_id;
		$workspace->doCurl_DELETE($url,$collection->AppKey);
		
		//report deletion
		$url='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/reports/'.$reports->report_guid;
		$workspace->doCurl_DELETE($url,$collection->AppKey);
		
        $this->findModel($id)->delete();	

        return $this->redirect(['index']);
    }
	
	/**
	* download the pbix file
	*
	*/
	
	public function actionDownload($file){
		
		$filepath=\Yii::$app->basePath.'/web/'.$file;
		if (file_exists($file)) {
		   return Yii::$app->response->sendFile($file);
		} 
		
	}
	
	/**
	* Report display
	*
	*/
	
	public function actionReport($id){

		$model = $this->findModel($id);
		if(!empty($model->report_id))
		{
			$Report = Reports::findOne($model->report_id);
			return $this->render('report', [
				'model' => $model,
			]);
		}
		else
		{
			$error = "<div class='alert alert-warning'><strong>Report is not Generated!</strong> Click the ".Html::a('link',['addpbix','id'=>$id])." to generate the report.</div>";
			//flash error message
			Yii::$app->session->setFlash('some_error', $error );
			return $this->actionIndex();
		}
	}
	
	/**
	*
	* Clone the dashboard
	* @return dashboard_name,description,collection_id,workspace_id,prefix
	*/
	
	public function actionCopyDashboard($id){
		
		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post()))
		{
			$dashboard = new Dashboard();
			$dashboard->scenario = 'clone';
			//$dashboard->attributes 	= $model->attributes;
			$dashboard->dashboard_name  = $model->dashboard_name;
			$dashboard->description		= $model->description;
			$dashboard->collection_id	= $model->collection_id;
			$dashboard->workspace_id	= $model->workspace_id;
			$dashboard->prefix			= $model->prefix;
			$dashboard->save();
			return $this->redirect(['index']);
		}
		else
		{
			$model->scenario = 'clone';
			return $this->render('clone',[
				'model'=>$model,
			]);
		}
	}

    /**
     * Finds the Dashboard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dashboard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dashboard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/* @Name:validateNamingConvention
	** @Def: To check Naming Convention wrt MSSQL
	** convention will follow PascalCase
    ** @Created:22-May-2017	
	*/
	public function validateNamingConvention($data,$model)
	{
		$errMsg = '';
		//$model = new Dashboard();
		$result = array('status'=>'success','msg'=>'');
		foreach($data as $key=>$sheets){
			$datamodel = new DataModel();
			$datamodel->model_name = $model->prefix."_".$key;
			$tables[] = $datamodel->model_name;
			if(!isset($sheets[0])){
				$model->addError("file","Excel file requires atleast one sheet.");
			}
			$headers = $sheets[0];
			$attributes = [];
			foreach($headers as $header=>$value){
				if($header!=''){
					if((strtolower($header) == 'id'))
						$attributes[] = ['field_name'=>$header,'field_type'=>'integer'];
					else $attributes[] = ['field_name'=>$header,'field_type'=>'text'];							
				}
			}					
			$attributes = serialize($attributes);
			//**Naming convention check starts ..sheet column
			$checkTableName = $this->checkNamingConvention($key,$datamodel->model_name,$attributes);
			if ($checkTableName['sheet']['status']=='error'){
				$errMsg.= "$key is not a valid SheetName, it should Alphabetic and Singular only."."\r\n";
			}
			if ($checkTableName['column']['status']=='error'){
				$errMsg.= "$key have invalid column: ".$checkTableName['column']['msg']." it should Alphabetic only."."\r\n";
			}
			//check ends..
		}
        if(!empty($errMsg)){
		   $result = array('status'=>'error','msg'=>$errMsg);
		}
		return $result;
	}
	
	/* @Name:checkNamingConvention
	** @Def: To check Naming Convention wrt MSSQL
	** convention will follow PascalCase
    ** @Created:22-May-2017	
	*/
	public function checkNamingConvention($sheetName,$tableName,$attributes)
	{
		$result['sheet'] = array('status'=>'success','msg'=>'');
		$result['column'] = array('status'=>'success','msg'=>'');
		//if (preg_match('/[^a-zA-Z_]/',$tableName)){
		if(!preg_match('/^[a-zA-Z_\/\s\d]+$/i',$tableName)){	
		    //Not a valid Name			
			$result['sheet'] = array('status'=>'error','msg'=>$sheetName);
		}
		$lastChar = substr($tableName, -1);
		if ($lastChar=='s' || $lastChar=='S'){			
			$result['sheet'] = array('status'=>'error','msg'=>$sheetName);
		}
		//check column names;
		$attributes = unserialize($attributes);
		$invalidColumn = array();
		foreach($attributes as $attribute){
			$columnName = $attribute['field_name'];
			//if (preg_match('/[^a-zA-Z_]/',$columnName)){
			if(!preg_match('/^[a-zA-Z_\/\s\d]+$/i',$columnName)){
		        //Not a valid column Name			    
				$invalidColumn[] = $columnName;		
		    }
		}
		if(count($invalidColumn)>0){
		   $strColumn = implode(",",$invalidColumn);
		   $result['column'] = array('status'=>'error','msg'=>$strColumn);
		}		
		return $result;
	}
	
}
