<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataModel */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Import Data Model';
$this->params['breadcrumbs'][] = ['label' => 'Data Models', 'url' => ['import']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="data-model-form">

<h2><?= Html::encode($this->title) ?></h2>

<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'prefix')->textInput() ?>
		<?= $form->field($model, 'file')->fileInput() ?>
		
    <div class="row form-group">		
        <?= Html::submitButton( 'Add' , ['class' => 'btn btn-success' ]) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
