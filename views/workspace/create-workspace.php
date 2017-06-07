<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Workspace;
use app\models\Collection;
/* @var $this yii\web\View */
/* @var $model app\models\Workspace */
/* @var $form ActiveForm */
$this->title = 'Create Workspace';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="powerbi-createworkspace">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

        <?php 
		if(isset($_REQUEST['collection_id']))
		{
			echo $form->field($model, 'collection_id')->dropDownList(ArrayHelper::map($collections,'collection_id','collection_name'), ['options'=>[$_REQUEST['collection_id']=>['selected'=>true]],'prompt'=>'Select Collection']);
		}
		else
		{
			echo $form->field($model, 'collection_id')->dropDownList(ArrayHelper::map($collections,'collection_id','collection_name'), ['prompt'=>'Select Collection']);
		}
		?>
        <?= $form->field($model, 'workspace_name')->textInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- powerbi-createworkspace -->
