<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TestData */

$this->title = 'Create Test Data';
$this->params['breadcrumbs'][] = ['label' => 'Test Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
