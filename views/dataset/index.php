<?php

use yii\helpers\Html; 
use yii\grid\GridView;
use app\models\Workspace;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Dataset */
/* @var $dataProvider yii\data\ActiveDataProvider */
if(isset($_REQUEST['w_id']))
{
	$workspace	= Workspace::findOne(['w_id'=>$_REQUEST['w_id']]);
	$this->title= $workspace->workspace_name.' Datasets';
}
else
{
	$this->title = 'Datasets';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dataset-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dataset', ['powerbi/create-dataset'], ['class' => 'btn btn-success']) ?>
		<?= isset($_REQUEST['w_id'])?Html::a('Sync', ['sync'], ['class' => 'btn btn-success','data'=>[
			'method'=>'POST',
			'params'=>['w_id'=>$_REQUEST['w_id']],
		]]):'' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            's_id',
            'dataset_name',
            'dataset_id',
            //'workspace_id',
			'workspace.workspace_name',
            'datasource_id',
            // 'gateway_id',
            ['class' => 'yii\grid\ActionColumn',
				'buttons' => [
				'delete'=>function($url){
					return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url,['data'=>[
						'method'=>'POST',
						'params'=>['w_id'=>isset($_REQUEST['w_id'])?$_REQUEST['w_id']:''],
					]]);
				}
			]
			],
        ],
    ]); ?>
</div>
