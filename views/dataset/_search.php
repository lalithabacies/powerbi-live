<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Dataset */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dataset-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 's_id') ?>

    <?= $form->field($model, 'dataset_name') ?>

    <?= $form->field($model, 'dataset_id') ?>

    <?= $form->field($model, 'workspace_id') ?>

    <?= $form->field($model, 'datasource_id') ?>

    <?php // echo $form->field($model, 'gateway_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
