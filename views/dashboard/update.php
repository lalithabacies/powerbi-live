<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dashboard */

$this->title = 'Update Dashboard: ' . $model->dashboard_id;
$this->params['breadcrumbs'][] = ['label' => 'Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dashboard_id, 'url' => ['view', 'id' => $model->dashboard_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dashboard-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
