<?php

namespace app\controllers;

use Yii;
use app\models\Dataset;
use app\models\search\Dataset as DatasetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Collection;
use app\models\Workspace;
use app\models\Reports;

/**
 * DatasetController implements the CRUD actions for Dataset model.
 */
class DatasetController extends Controller
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
     * Lists all Dataset models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DatasetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		isset($_REQUEST['w_id'])?$dataProvider->query->andFilterWhere(['workspace_id'=>$_REQUEST['w_id']]):'';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dataset model.
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
     * Creates a new Dataset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Dataset();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->s_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Dataset model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->s_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Dataset model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$dataset 	= Dataset::findOne(['s_id'=>$id]);
		$workspace	= Workspace::find()->where(['w_id'=>$dataset->workspace_id])->one();
		$collection	= Collection::find()->where(['collection_id'=>$workspace->collection_id])->one();
		$report = Reports::find()->where(['workspace_id'=>$dataset->workspace_id])->exists();
		($report)?Reports::findOne(['workspace_id'=>$dataset->workspace_id])->delete():'';
        $this->findModel($id)->delete();
		
		$url='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/datasets/'.$dataset->dataset_id;
		$workspace->doCurl_DELETE($url,$collection->AppKey);
	
		if(isset($_REQUEST['w_id']))
		{
			return $this->redirect(['index','w_id'=>$_REQUEST['w_id']]);
		}
		else
		{
			return $this->redirect(['index']);
		}
    }

    /**
     * Finds the Dataset model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dataset the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dataset::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
	* Syncronize all the data which has been occured in the portal.azure.com
	* And get stores in this model.
	*/
	public function actionSync()
	{
		$workspaces= Workspace::findOne(['w_id'=>$_REQUEST['w_id']]);
		$collection= Collection::findOne(['collection_id'=>$workspaces->collection_id]);
		$datasets  = Dataset::find()->where(['workspace_id'=>$_REQUEST['w_id']])->all();
		$url = 'https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspaces->workspace_id.'/datasets';
		$response = json_decode($workspaces->doCurl_GET($url,$collection->AppKey)); 

		$dataset_id = array();
		foreach($datasets as $data){
			$dataset_id[]=$data->dataset_id;
		}
		foreach($response->value as $data_set){
			if(!in_array($data_set->id,$dataset_id)){
				
				//Returns the datasource id,gateway id
				$end_url ='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspaces->workspace_id.'/datasets/'.$data_set->id.'/Default.GetBoundGatewayDatasources';

				$respns_ds_gw = json_decode($workspaces->doCurl_GET($end_url,$collection->AppKey));
				if(isset($respns_ds_gw->value))
				{
				foreach($respns_ds_gw->value as $gateway)
				{
				$dataset   = new Dataset;	
				$dataset->dataset_id	= $data_set->id;
				$dataset->dataset_name	= $data_set->name;
				$dataset->datasource_id = $gateway->id;
				$dataset->gateway_id 	= $gateway->gatewayId; 
				$dataset->workspace_id  = $_REQUEST['w_id'];
				$dataset->save(false);
				}
				}
			}
		}
		return $this->redirect(['dataset/index','w_id'=>$_REQUEST['w_id']]);
	}
}
