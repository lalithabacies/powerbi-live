<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Workspace */

$this->title = $model->w_id;
$this->params['breadcrumbs'][] = ['label' => 'Workspaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert alert-warning">
  <strong>Report is not Generated!</strong> Click the <?= Html::a('link',['reports/create-report','w_id'=>$_REQUEST['id']])?> to generate the report.
</div>
