<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Dashboard */

$this->title = 'Clone Dashboard';
$this->params['breadcrumbs'][] = ['label' => 'Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$model->dashboard_name	=	$model->dashboard_name.'_copy';
?>
<div class="dashboard-create">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<div class="alert alert-info">
	  <strong>Clone!</strong> Do you want to really clone the dashboard by the following name?.
	</div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dashboard_name')->textInput(['value'=>$model->dashboard_name]) ?>
	
    <div class="form-group">
        <?= Html::submitButton('OK', ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Cancel', ['index'], ['class'=>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
