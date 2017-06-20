<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Form-Generator:'.$model->dashboard_name;
?>
<div class="container">
	<h1><?= Html::encode($this->title) ?></h1>
	
	<!-- Vertical Tabs Starts here -->
    <div class="tab col-md-2">	
		<?php 
		$i=1;
		if(!empty($tables))
		{
		foreach($tables as $name=>$table){ ?>
		  <button class="tablinks" onclick="openTab(event, '<?=$name?>')" id="<?php echo $i==1?"defaultOpen":""?>"><?=$name?></button>
		<?php $i++; }} ?>
    </div>		
	<!-- Vertical Tabs Ends here -->

	<!-- MAIN PANEL FOR EACH TAB -->
	<?php 
	$i=1;
	$form = ActiveForm::begin();
	if(!empty($tables)){
	foreach($tables as $name=>$fields){ ?>
    <div class="demo tabcontent col-md-10" id="<?=$name?>">
	<?php  
		$tab_name = preg_replace('/\s+/', '', $name);
		$name_exploded = explode("_",$name);		
	?>
	<!-- FORM TITLE AND PUBLISH STATUS -->
      <div class="your-class col-md-12">
			<div class="form-group fn col-md-10">
			  <input class="form-control form-title" placeholder="Enter the form title" type="text" name="tables[<?=$tab_name?>][form_title]" value="<?=$name_exploded[1]?>" >
			  <span class="underline"></span>
			</div>
			<div class="form-group cb col-md-2">
			Publish
			  <input type="hidden" name="tables[<?=$tab_name?>][is_published]" value='0'/>
			  <input type="checkbox" data_tid="<?=$tab_name?>" id="<?=$tab_name?>_visible" class="un-select" name="tables[<?=$tab_name?>][is_published]" value='1' checked="true"/>
			  <label for="<?=$tab_name?>_visible" class="check-box"></label>
			</div>
	<input type="hidden" name="tables[<?=$tab_name?>][model_name]" value="<?=$name?>" >
      </div>
	<!-- FORM TITLE AND PUBLISH STATUS -->

	<!-- ATTRIBUTES/FIELDS START FROM HERE -->
      <div class="panel-group <?=$tab_name?>_panel" id="accordion" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default">
			<?php 
			$f_index = 0;
			foreach($fields['attributes'] as $field){
			$field_name = preg_replace('/\s+/', '', $field['field_name']);
			$identifier = "{$tab_name}_{$field_name}";	
			?>
			<!-- FIELD HEADING -->
			<div class="panel-heading" role="tab" id="header_<?=$identifier?>">
				<h4 class="panel-title">
				  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?=$identifier?>" aria-expanded="true" aria-controls="<?=$identifier?>">
					<i class="more-less glyphicon glyphicon-plus"></i>
					<?= $field['field_name'] ?>
				  </a>
				</h4>
			</div>
			<!-- FIELD HEADING -->
			
			<!-- FIELD DETAILS -->		  
			<div id="<?=$identifier?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="header_<?=$identifier?>">
				<div class="panel-body">
					<div class="col-md-12">
					
						<!-- FIELD NAME -->
						<div class="col-md-3">
						  <div class="form-group field-name">
							<label for="f-name">Field Name</label>
							<input type="hidden" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][field_name]"  value="<?= $field['field_name'] ?>">
							<input type="text" class="form-control" id="id-fieldname" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][field_display_name]" value="<?= $field['field_name'] ?>">
						  </div>
						</div>
						<!-- FIELD NAME -->
						
						<!-- FIELD TYPE -->
						<div class="col-md-3">
						  <select class="form-control field_type select-options" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][field_type]" id="<?=$identifier?>">
							<!--<option value="">--Select Field Type--</option>-->
							<option value="hidden">Hidden</option> <!-- No Secondary Options -->
							<option value="text">Text Input</option> <!-- No Secondary Options -->
							<option value="textarea">Text Area</option> <!-- No Secondary Options -->
							<option value="date-input">Date Input</option> 
							<option value="dropdown">DropDown List</option>
							<option value="default-value">Text Input with Default value</option>
							<option value="radio-button">Radio Button</option>
						  </select>
						</div>
						<!-- FIELD TYPE -->
						
						<!-- FIELD TYPE OPTIONS -->
						<div class="col-md-3">
							
							<div class="form-group d-format" id="<?=$identifier?>_d-format">
								<label>Selecct a date format:</label>
								<select class="date_format_select form-control" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][options][dateformat]">
									<option value="mm/dd/yy">Default - mm/dd/yy</option>
									<option value="yy-mm-dd">ISO 8601 - yy-mm-dd</option>
									<option value="d M, y">Short - d M, y</option>
									<option value="d MM, y">Medium - d MM, y</option>
									<option value="DD, d MM, yy">Full - DD, d MM, yy</option>
									<option value="'day' d 'of' MM 'in the year' yy">With text - 'day' d 'of' MM 'in the year' yy</option>
								</select>
							</div>
							
							<div class="dropdown column-dropdown" id="<?=$identifier?>_column-dropdown">
								<label for="default-value">DropDown Options</label>
								<textarea class="form-control" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][options][dropdown_options]" placeholder="Enter default values as comma separeted"></textarea>
							</div>
							
							<div class="form-group default-value" id="<?=$identifier?>_default-value">
							  <label for="default-value">Default Value</label>
							  <input type="text" class="form-control" id="id-default-value" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][options][default_text]">
							</div>
							
							<div class="form-group radio-button" id="<?=$identifier?>_radio-button" style='display:none;'>
							  <label for="default-value">Radio Button</label>
							  <input type="radio" class="form-control" id="id-radio-button" name="tables[<?=$tab_name?>][fields][<?=$f_index?>][options][radio_buttons]">
							</div>

							
						</div>
						<!-- FIELD TYPE OPTIONS -->

					</div>

				</div>
			</div>
			<!-- FIELD DETAILS -->
			<?php $f_index++; } ?>
        </div>


        </div>
		<!-- ATTRIBUTES/FIELDS START FROM HERE -->
		

      </div><!-- demo -->
		
	<?php $i++; }} ?>
	<div class="save-btn">
	  <?= Html::submitButton( 'Save' , ['class' =>  'btn btn-success']) ?>
	</div>
	<?php ActiveForm::end(); ?>
	<!-- MAIN PANEL FOR EACH TAB ENDS HERE-->



</div><!--container-->

<script>
	function openTab(evt, cityName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
		  tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
		  tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
	}

	// Get the element with id="defaultOpen" and click on it
	document.getElementById("defaultOpen").click();
	//accordian plus minus icon

	$(document).ready(function () {
		 $('.collapse').on('shown.bs.collapse', function(){
			 
			//$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
			
			 var id = $(this).attr("id");			 
			 $("#header_"+id).find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
						
			}).on('hidden.bs.collapse', function(){
				
			//$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
			
			var id = $(this).attr("id");			 
			$("#header_"+id).find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
						
			}); 
	});


//dropdown

	$(function () {
		$(".field_type").change(function () {
			
			var selectedValue = $(this).val();
			var selectedField = $(this).attr("id");
			var all_options = "#"+selectedField+"_d-format, #"+selectedField+"_column-dropdown, #"+selectedField+"_default-value, #"+selectedField+"_hidden-with , #"+selectedField+"_radio-button";

			if(selectedValue == "date-input"){
				$(all_options).hide();
				$("#"+selectedField+"_d-format").show();
			}
			else if(selectedValue == "dropdown"){
				$(all_options).hide();
				$("#"+selectedField+"_column-dropdown").show();
			}
			else if(selectedValue == "default-value"){
				$(all_options).hide();
				$("#"+selectedField+"_default-value").show();
			}
			else if(selectedValue == "radio-button"){
				$(all_options).hide();
				$("#"+selectedField+"_radio-button").show();
			}
			else{
				$(all_options).hide();
			}	
			
		});
	});


	//checkbox

	$(document).ready(function(){
		$(".un-select").change(function() {
			var selected = $(this).attr("data_tid");
			if($(this).is(":checked")) {
				$(".active").css('background-color', '#7FDE8D');
				$("."+selected+"_panel").css("opacity","1");
				$("."+selected+"_panel").css("pointer-events","");
			}
			else {
				$(".active").css('background-color', '#F26161');
				$("."+selected+"_panel").css("opacity","0.5");
				$("."+selected+"_panel").css("pointer-events","none");
		//alert("yesss");
			}
	});
	});
</script>