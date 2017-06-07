<?php

namespace app\controllers;

use Yii;
use app\models\Workspace;
use app\models\search\Workspace as WorkspaceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Collection;
use app\models\Dataset;
use app\models\Reports;

/**
 * WorkspaceController implements the CRUD actions for Workspace model.
 */
class WorkspaceController extends Controller
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
     * Lists all Workspace models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new WorkspaceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		isset($_REQUEST['collection_id'])?$dataProvider->query->andFilterWhere(['collection_id'=>$_REQUEST['collection_id']]):'';

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Workspace model.
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
     * Creates a new Workspace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Workspace();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->w_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Workspace model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->w_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Workspace model and also dataset and reports that are connected to this.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$workspace	= Workspace ::find()->where(['w_id'=>$id])->one();
		$collection	= Collection::find()->where(['collection_id'=>$workspace->collection_id])->one();
		$dataset 	= Dataset::find()->where(['workspace_id'=>$id])->all();
		
		Dataset::findOne(['workspace_id'=>$id])->delete();		
		Reports::findOne(['workspace_id'=>$id])->delete();
        $this->findModel($id)->delete();
		
		foreach($dataset as $data_set){
		$url='https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces/'.$workspace->workspace_id.'/datasets/'.$data_set->dataset_id;
		$workspace->doCurl_DELETE($url,$collection->AppKey);
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the Workspace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Workspace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Workspace::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
	*
	* @return the dropdown list of requested collection_id
	*/
	
	public function actionWorkspaceslist($collection_id)
	{
		$workspace  = Workspace::find()->where(['collection_id'=>$collection_id])->orderBy('workspace_name ASC')->all();
        $data	='<option value="0">Select Workspace</option>';
        if($workspace)
        {
            foreach ($workspace as $result){
                $data.="<option value='".$result->w_id."'>".$result->workspace_name."</option>";
            }
        }
        else
        {
            $data.="<option value='0'>--</option>";
        }
        echo $data;
	}
	
	/**
	* Syncronize all the data which has been occured in the portal.azure.com
	* And get stores in this model.
	*/
	public function actionSync()
	{
		$collection= Collection::findOne(['collection_id'=>$_REQUEST['collection_id']]);
		$workspaces		= Workspace::find()->where(['collection_id'=>$_REQUEST['collection_id']])->all();
		$url = 'https://api.powerbi.com/v1.0/collections/'.$collection->collection_name.'/workspaces';
		$response = json_decode(Workspace::doCurl_GET($url,$collection->AppKey)); 

		$workspace_id = array();
		foreach($workspaces as $work){
			$workspace_id[]=$work->workspace_id;
		}
		if(isset($response->value)){
		foreach($response->value as $coll){
			if(!in_array($coll->workspaceId,$workspace_id)){
			$workspace = new Workspace;
			$workspace->workspace_name 	= $coll->displayName;
			$workspace->workspace_id	= $coll->workspaceId;
			$workspace->collection_id	= $_REQUEST['collection_id'];
			$workspace->save();
			}
		}
		}
		return $this->redirect(['workspace/index','collection_id'=>$_REQUEST['collection_id']]);
		
	}
	
	/**
	* @creating workspace
	* @returns workspace_id
	*/
    public function actionCreateWorkspace()
    {
		$workspace      = new Workspace();  
		$collections 	= Collection::find()->all();
                
		if($workspace->load(Yii::$app->request->post())){
            $collection 	= Collection::findOne($workspace->collection_id);
			$end_url		='https://api.powerbi.com/v1.0/collections/';
            $end_url        .= $collection->collection_name;
            $end_url        .='/workspaces';
			$access_key	= $collection->AppKey;
			$params = "name={$workspace->workspace_name}";
                        $response       = json_decode($workspace->doCurl_POST($end_url,$access_key,$params,"application/x-www-form-urlencoded","POST"));
                        if(isset($response->error)){
                            //flash error message
                            Yii::$app->session->setFlash('some_error',  $response->error->message);
                            return $this->render('create-workspace',[
								'model'=>$workspace,
                                'collections' => $collections,
                            ]);
                        }
                        $workspace->workspace_id = $response->workspaceId;
						$workspace->save(false);

                        return $this->redirect(['workspace/index']);
		}
		else
		{
			return $this->render('create-workspace',[
				'model'=>$workspace,
                'collections' => $collections,
			]);
		}
    }
}
