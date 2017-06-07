<?php

namespace app\controllers;

use Yii;
use app\models\Reports;
use app\models\search\ReportsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Workspace;
use app\models\Collection;
use app\models\Dataset;

/**
 * ReportsController implements the CRUD actions for Reports model.
 */
class ReportsController extends Controller
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
     * Lists all Reports models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reports model.
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
     * Creates a new Reports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reports();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->r_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reports model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->r_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Reports model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$reports	= Reports::findOne(['r_id'=>$id]);
		$dataset 	= Dataset::findOne(['dataset_id'=>$reports->dataset_id]);
		$workspace	= Workspace::find()->where(['w_id'=>$dataset->workspace_id])->one();
		$collection	= Collection::find()->where(['collection_id'=>$workspace->collection_id])->one();
		$url='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/reports/'.$reports->report_guid;
		$workspace->doCurl_DELETE($url,$collection->AppKey);
		
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Reports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reports::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionCreateReport($w_id)
	{	
		$workspace  = Workspace::findOne($w_id);
		$collection = Collection::findOne($workspace->collection_id);
		
		$url="https://api.powerbi.com/v1.0/collections/".$collection->collection_name."/workspaces/".$workspace->workspace_id."/reports";
		
		$response = json_decode($workspace->doCurl_GET($url,$collection->AppKey));
		foreach($response->value as $res){
			$model  	= new Reports();
			$model->report_name = $res->name;
			$model->report_guid = $res->id;
			$model->web_url		= $res->webUrl;
			$model->embed_url	= $res->embedUrl;
			$model->dataset_id	= $res->datasetId;
			$model->workspace_id= $w_id;
			$model->save(false);
		}
		return $this->redirect(['workspace/index']);	
	}
}
