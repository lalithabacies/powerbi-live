<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dataset */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dataset-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dataset_name')->textInput() ?>

    <?= $form->field($model, 'dataset_id')->textInput() ?>

    <?= $form->field($model, 'workspace_id')->textInput() ?>

    <?= $form->field($model, 'datasource_id')->textInput() ?>

    <?= $form->field($model, 'gateway_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
