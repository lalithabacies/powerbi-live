<?php

namespace app\controllers;

use Yii;
use app\models\DataModel;
use app\models\ImportForm;
use app\models\search\DataModel as DataModelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Customer;

/**
 * DataModelController implements the CRUD actions for DataModel model.
 */
class DataModelController extends Controller
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
     * Lists all DataModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DataModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataModel();

        if ($model->load(Yii::$app->request->post())) {
			//print_r(Yii::$app->request->post());die;
			$model->attributes = serialize(Yii::$app->request->post()['fields']);
			$model->save();
			
            return $this->redirect(['view', 'id' => $model->m_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	public function actionImport(){
		
		$model = new ImportForm();
		
		if ($model->load(Yii::$app->request->post())) {
			set_time_limit(0);
			$model->file = UploadedFile::getInstance($model, 'file');
			if ($model->upload()) {
                // file is uploaded successfully
				$data = \moonland\phpexcel\Excel::import(\Yii::$app->basePath."/web/uploads/". $model->file->baseName . '.' . $model->file->extension, [
					'setFirstRecordAsKeys' => true, 
					'setIndexSheetByName' => true, 
				]);
				foreach($data as $key=>$sheets){
					$datamodel = new DataModel();
					$datamodel->model_name = $model->prefix.$key;
					//$datamodel->prefix = $model->prefix;
					$headers = $sheets[0];
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
						// save data too
						foreach($sheets as $header=>$data){
							foreach($data as $key=>$d){
								//eliminate the null keys
								if($key == '')
									unset($data[$key]);
							}
							$data['eq_customer_id'] = \Yii::$app->user->id;
							\Yii::$app->db->createCommand()
								->insert($datamodel->model_name, $data)->execute();
						}
					}
				}
				//print_r($data);die;
                return;
            }
			//foreach sheet
			//{new datamodel, input sample data}
			
		}else{
            return $this->render('import', [
                'model' => $model,
            ]);
        }
			
	}
	public function actionImportData(){
		
		$model = new ImportForm();
		
		if ($model->load(Yii::$app->request->post())) {
			set_time_limit(0);
			$model->file = UploadedFile::getInstance($model, 'file');
			if ($model->upload()) {
                // file is uploaded successfully
				$data = \moonland\phpexcel\Excel::import(\Yii::$app->basePath."/web/uploads/". $model->file->baseName . '.' . $model->file->extension, [
					'setFirstRecordAsKeys' => true, 
					'setIndexSheetByName' => true, 
				]);
				foreach($data as $key=>$sheets){
					$datamodel= $this->findModel($id);
					if($datamodel->save()){
						// save data too
						foreach($sheets as $header=>$data){
							foreach($data as $key=>$d){
								//eliminate the null keys
								if($key == '')
									unset($data[$key]);
							}
							$data['eq_customer_id'] = \Yii::$app->user->id;
							\Yii::$app->db->createCommand()
								->insert('customer_'.$key, $data)->execute();
						}
					}
				}
				//print_r($data);die;
                return $this->redirect(['index']);
            }
			//foreach sheet
			//{new datamodel, input sample data}
			
		}else{
            return $this->render('import', [
                'model' => $model,
            ]);
        }
			
	}
	public function actionFormData(){
		//static
		$tables = [
		'2_Technology','2_Social','2_MockupHeatmap'
		];
		$output = [];
		foreach($tables as $table){
			$model = DataModel::find()->where(['model_name'=>$table])->one();
			$output[] = ['name'=>$table,'attributes'=>$model->attributes];
		}
		return json_encode($output);
	}
    /**
     * Updates an existing DataModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->m_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DataModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DataModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionTest(){
		$queryBuilder = new \yii\db\Migration();
		$queryBuilder->createTable('myTable', [
			'id' => 'pk',
			'myColumn' => 'integer',
			'myOtherColumn' => 'text'
		]);
	}
	
	public function actionAddData($id){
		
		$model = $this->findModel($id);
		$attributes = unserialize($model->attributes);
		if(\Yii::$app->request->post()){
			//save data
		}
		else
		return $this->render('add_data',[
			'attributes'=>$attributes,
			'model' => $model
		]);
		
	}
	
	/*
	 * This is for API
	 * Params: dashboard ID
	 * Return boolean
	 */
	public function actionSaveData(){
		//$this->csrf
		if(\Yii::$app->request->post()){
			$customer_id = \Yii::$app->request->post()['attributes']['eq_customer_id'];
			$customer = Customer::find()->where(['eq_customer_id'=>$customer_id])->one();
			if(!$customer){
				$customer = new Customer();
				$customer->eq_customer_id = $customer_id;
				$customer->save();
			}
			\Yii::$app->db->createCommand()
				->insert(\Yii::$app->request->post()['table_name'], \Yii::$app->request->post()['attributes'])
				->execute();	
			return $this->redirect(\Yii::$app->request->post()['redirect_url']);				
		}
		else return false;

	}
	public function actionAddForm($id){
		
		$model = $this->findModel($id);
		$attributes = unserialize($model->attributes);
		if(\Yii::$app->request->post()){
			//save data
		}
		else
		return $this->render('add_form',[
			'attributes'=>$attributes,
			'model' => $model
		]);
		
	}
	/**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{            
		if ($action->id == 'save-data') {
			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction($action);
	}
}
