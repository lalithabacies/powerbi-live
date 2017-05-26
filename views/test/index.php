<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TestData */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Test Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Test Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'data_id',
            'x',
            'y',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
