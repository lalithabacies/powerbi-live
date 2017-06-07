<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Collection;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Workspace */
/* @var $dataProvider yii\data\ActiveDataProvider */

if(isset($_REQUEST['collection_id']))
{
	$collection	= Collection::findOne(['collection_id'=>$_REQUEST['collection_id']]);
	$this->title= $collection->collection_name.' Workspaces';
}
else
{
	$this->title = 'Workspaces';
}	
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workspace-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Workspace', ['workspace/create-workspace'], ['class' => 'btn btn-success','data'=>[
					'method'=>'POST',
					'params'=>[
						'collection_id'=>isset($_REQUEST['collection_id'])?$_REQUEST['collection_id']:'',
					]
		]]) ?>
		<?= isset($_REQUEST['collection_id'])?Html::a('Sync', ['sync'], ['class' => 'btn btn-success','data'=>[
			'method'=>'POST',
			'params'=>['collection_id'=>$_REQUEST['collection_id']],
		]]):'' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' 	=> $dataProvider,
        'filterModel' 	=> $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'w_id',
            //'workspace_name',
			[
				'label' =>'workspace name',
				'format'=>'raw',
				'value' =>function($data){
					return Html::a($data->workspace_name,['dashboard/index'],[
					'data'=>[
						'method'=>'GET',
						'params'=>['workspace_id'=>$data->w_id,'collection_id'=>$data->collection_id],
					]
					]);
				}
			],
            'workspace_id',
            //'collection_id',
            'collection.collection_name',
            //['class' => 'yii\grid\ActionColumn'],
			[
				'label'=>'Report',
				'format'=>'raw',
				'value'=>function ($data) {
					return Html::a('Generate',['reports/create-report','w_id'=>$data->w_id]);
				},
			]
        ],
    ]); ?>
</div>
