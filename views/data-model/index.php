<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DataModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-model-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Data Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'm_id',
            'model_name',
            [
			'label'=>'Add data',
			'format' => 'raw',
			'value' => function($data){
				return Html::a(Html::encode("Add"),'add-data?id='.$data->m_id);
			}
			],
			
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
