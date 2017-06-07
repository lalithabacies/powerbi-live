<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Workspace;
use app\models\Collection;
use app\models\Dataset;
/* @var $this yii\web\View */
/* @var $model app\models\Workspace */
/* @var $form ActiveForm */
$this->title = 'Create Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
$(function(){
	$('#dataset-dashboard_name').blur(function(){
		$('#dataset-dataset_name').val($(this).val());
	});
});
</script>

<div class="powerbi-createworkspace">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
		
		<?php
		/*echo $form->field($model, 'dashboard_name')->textInput();
		echo $form->field($model, 'description')->textArea();
		echo $form->field($model, 'collection_id')->dropDownList(ArrayHelper::map($collections,'collection_id','collection_name'), ['prompt'=>'Select Collection','onChange'=>'$.get("'.Yii::$app->urlManager->createUrl('workspace/workspaceslist?collection_id=').'"+$(this).val(),function(data){$("#dataset-workspace_id").html(data);})',]);
        echo $form->field($model, 'workspace_id')->dropDownList([''=>'Select Workspace']);
        echo $form->field($model, 'dataset_name')->textInput();*/
		?>
		<?= $form->field($model, 'file')->fileInput() ?>
    
        <div class="form-group">
			<?php
			/*Html::hiddenInput('Dashboard[dashboard_id]', $_REQUEST['id'])*/ ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- powerbi-createworkspace -->
