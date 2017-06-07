<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Dashboard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dashboard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dashboard_name')->textInput() ?>

    <?= $form->field($model, 'description')->textInput() ?>

	<?= $form->field($model, 'collection_id')->dropDownList(ArrayHelper::map($collections,'collection_id','collection_name'), [
	'options'=>isset($_REQUEST['collection_id'])?([$_REQUEST['collection_id']=>['selected'=>true]]):'',
	'prompt'=>'Select Collection','onChange'=>'$.get("'.Yii::$app->urlManager->createUrl('workspace/workspaceslist?collection_id=').'"+$(this).val(),function(data){$("#dataset-workspace_id").html(data);})',])?>
	<?= $form->field($model, 'workspace_id')->dropDownList([''=>'Select Workspace']) ?>	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
