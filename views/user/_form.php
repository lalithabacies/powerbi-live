<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$roles=[
	'admin'  =>'Admin',
    'system admin'  =>'System Admin',
    'user'  =>'User',
];

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>
	
    <?= $form->field($model, 'email')->textInput() ?>
	
	<?= $form->field($model, 'role')->dropDownList($roles,['prompt'=>'--Select The Role--']); ?>
  
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
