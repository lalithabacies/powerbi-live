<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Reports */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reports-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'report_guid')->textInput() ?>

    <?= $form->field($model, 'report_name')->textInput() ?>

    <?= $form->field($model, 'web_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'embed_url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'workspace_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
