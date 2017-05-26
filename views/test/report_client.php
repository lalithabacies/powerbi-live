<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TestData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-data-form">
<?php
    // 1. power bi access key
    $accesskey = "o7rSltTDXbKMlWlj3XhAf8KLjq1Tfprs3HDlhXG8En6qdxbvT+s8a4g8+z29p3vCA41ZgZg+qhjtmUvKdCCBJA==";

    // 2. construct input value
    $token1 = "{" .
      "\"typ\":\"JWT\"," .
      "\"alg\":\"HS256\"" .
      "}";
    $token2 = "{" .
      "\"wid\":\"37380bc1-dd47-4c95-8dbd-5efecafc8b26\"," . // workspace id
      "\"rid\":\"e5a9d88b-d5a1-4798-8ebc-b220a6dd49af\"," . // report id
      "\"wcn\":\"washington\"," . // workspace collection name
      "\"iss\":\"PowerBISDK\"," .
      "\"ver\":\"0.2.0\"," .
      "\"aud\":\"https://analysis.windows.net/powerbi/api\"," .
      "\"nbf\":" . date("U") . "," .
      "\"exp\":" . date("U" , strtotime("+1 hour")) .
      "}";
    $inputval = rfc4648_base64_encode($token1) .
      "." .
      rfc4648_base64_encode($token2);

    // 3. get encoded signature value
    $hash = hash_hmac("sha256",
        $inputval,
        $accesskey,
        true);
    $sig = rfc4648_base64_encode($hash);

    // 4. get apptoken
    $apptoken = $inputval . "." . $sig;

    // helper functions
    function rfc4648_base64_encode($arg) {
      $res = $arg;
      $res = base64_encode($res);
      $res = str_replace("/", "_", $res);
      $res = str_replace("+", "-", $res);
      $res = rtrim($res, "=");
      return $res;
    }
    ?>



<div id="reportContainer"  style="height:800px"></div>
    <script src="http://localhost/powerbi/web/js/node_modules/powerbi-client/dist/powerbi.min.js"></script>
    <script>
        (function () {
			var models = window['powerbi-client'].models;
 			//console.log(models);
            var embedToken = '<?=$apptoken?>';
            var reportId = 'e5a9d88b-d5a1-4798-8ebc-b220a6dd49af';
            var embedUrl = 'https://embedded.powerbi.com/appTokenReportEmbed?reportId' + reportId+'"&$filter=CountryRegion/CountryRegionName eq \'United States\'";';
			var $defaultPageReportContainer = $('#reportContainer');
			
			var defaultFilter =  models.AdvancedFilter({
				  table: "customer_Risk",
				  column: "eq_customer_id"
				}, "And", [
				  {
					operator: "Is",
					value: 2
				  }
				]);

		  var defaultFilters = [defaultFilter];
		  
            var config = {
                type: 'report',
                accessToken: embedToken,
                embedUrl: embedUrl,
                id: reportId,
				filter: defaultFilters,
                settings: {
                    filterPaneEnabled: true,
                    navContentPaneEnabled: true
                }
            };

             powerbi.embed(document.getElementById('reportContainer'), config);

		
		})();
    </script>


