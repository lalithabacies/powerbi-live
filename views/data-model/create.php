<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DataModel */

$this->title = 'Create Data Model';
$this->params['breadcrumbs'][] = ['label' => 'Data Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
