<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Collection */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Collections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Collection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'collection_id',
            //'collection_name',
			[
				'label'=>'Collection Name',
				'format'=>'raw',
				'value'=>function ($data) {
					return Html::a($data->collection_name,['workspace/index'],[
					'data'=>[
						'method'=>'GET',
						'params'=>['collection_id'=>$data->collection_id],
					],
					]);
				},
			],
            'AppKey',
			[
				'label'=>'Workspace',
				'format'=>'raw',
				'value'=>function($data){
					return Html::a('Add Workspace',['workspace/create-workspace'],[
						'data'=>[
							'method'=>'POST',
							'params'=>[
								'collection_id'=>$data->collection_id
							]
						]
					]);
				}
			],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
