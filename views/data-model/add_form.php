<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataModel */
/* @var $form yii\widgets\ActiveForm */
$this->title = $model->model_name.': Add data';
$this->params['breadcrumbs'][] = ['label' => 'Data Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="data-model-form">

<h2><?= Html::encode($this->title) ?></h2>

	<?php $form = ActiveForm::begin(); ?>
	
	<?php 
		foreach($attributes as $attribute){
			$name = $attribute['field_name'];
			$type = $attribute['field_type'];
			echo '<div class="row form-group">';
				echo "<input type='text' name='$name' placeholder='$name' class='form-control'/>";
			echo '</div>';
		}
	?>
	
    <div class="row form-group">
        <?= Html::submitButton( 'Add' , ['class' => 'btn btn-success' ]) ?>
    </div>

	<?php ActiveForm::end(); ?>
</div>
