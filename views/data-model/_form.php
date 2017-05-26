<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DataModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model_name')->textInput() ?>

	<div class="fields">
		<h4>Add Attributes</h4>
		<div class="field row">
			<div class="row pull-right" onclick="removeMe(this)"><i class="glyphicon glyphicon-remove"></i></div>
			<div class="col-md-6">
				<input class="form-control" type="text" name="fields[1][field_name]" placeholder="Attribute Name here" required/>
			</div>
			<div class="col-md-6">
				<select class="form-control" type="text" name="fields[1][field_type]"/>
					<option value="integer">Integer</option>
					<option value="text">Text</option>
				</select>
			</div>
		</div>
	</div>
	<br>
	<div class="row pull-right">
		<button type="button" class="btn btn-default col-sm-offset-1 addmore-button">Add Attribute</button>
	</div>
	
    <div class="row form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div id="to_copy" style="display:none">
		<div class="row pull-right" onclick="removeMe(this)"><i class="glyphicon glyphicon-remove"></i></div>
		<div class="col-md-6">
			<input class="form-control" type="text" name="fields[0][field_name]" placeholder="Attribute Name here" required/>
		</div>
		<div class="col-md-6">
			<select class="form-control" type="text" name="fields[0][field_type]"/>
				<option value="integer">Integer</option>
				<option value="text">Text</option>
			</select>
		</div>

</div>
<script>
	var next = $(".field").length;
	$(".addmore-button").click(function(){
		$(".btn-group").hide();
		var htm = $('#to_copy').html();
		//return false;
		next = next +1;
		//var o_html = $('#first').html();
		var html = htm.replace(/0/g, next);
		html = '<div class="field row"><div class="row pull-right" onClick="removeMe(this)"><i class="glyphicon glyphicon-remove" ></i></div>'+html+"</div>";
		$( ".fields" ).append( html );
		//console.log(next);

	});
	function removeMe(element){
		$(element).parent('div').remove();
	}
</script>