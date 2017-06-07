<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="password-reset">

	
Hello <?php  echo $user->username; ?>,
<br><p> Please use this link to login :<?php echo (Url::to(["site/login"], TRUE)) ?>
<br>
<?php echo "Username: ".$user->username."<br> Password: ".$password."</p>"; ?>
<br>
<br>
Thanks and Regards,<br>
PowerBi Teams.
<br>



</div>
