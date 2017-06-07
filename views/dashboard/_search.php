<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Dashboard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dashboard-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dashboard_id') ?>

    <?= $form->field($model, 'dashboard_name') ?>

    <?= $form->field($model, 'pbix_file') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'models') ?>

    <?php // echo $form->field($model, 'report_id') ?>

    <?php // echo $form->field($model, 'form_data') ?>

    <?php // echo $form->field($model, 'workspace_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
