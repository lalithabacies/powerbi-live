<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TestData */

$this->title = 'Update Test Data: ' . $model->data_id;
$this->params['breadcrumbs'][] = ['label' => 'Test Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->data_id, 'url' => ['view', 'id' => $model->data_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="test-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
