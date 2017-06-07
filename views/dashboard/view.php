<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Dashboard */

$this->title = $model->dashboard_id;
$this->params['breadcrumbs'][] = ['label' => 'Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dashboard_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dashboard_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'dashboard_id',
			[
				'attribute'=>'collection_name',
				'value' => $collection->collection_name,
			],
			'workspace.workspace_name',
            'dashboard_name',
            //'pbix_file:ntext',
			[
				'attribute'=>'pbix_file',
				'value' => function($data){
					if(isset($data->pbix_file))
					{
						$file=explode("/",$data->pbix_file);
						return $file[1];
					}
					else
					{
						return '';
					}
				},
			],
            'description',
            'models:ntext',
            //'report_id:ntext',
            //'form_data:ntext',
            //'workspace_id',
			[
				'attribute'=>'report_guid',
				'value' => isset($reports->report_guid)?$reports->report_guid:'',

			],
			[
				'attribute'=>'report_name',
				'value' => isset($reports->report_name)?$reports->report_name:'',

			],
			[
				'attribute'=>'web_url',
				'value' => isset($reports->web_url)?$reports->web_url:'',

			],
			[
				'attribute'=>'embed_url',
				'value' => isset($reports->embed_url)?$reports->embed_url:'',

			],
			
        ],
    ]) ?>

</div>
