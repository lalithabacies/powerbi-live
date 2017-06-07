<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$roles_array = array( 'admin'  =>'Admin', 'system admin'  =>'System Admin', 'user'  =>'User');

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'username',
			'email:email',			
			
			[
                'attribute' => 'role',
                'label' => 'Role',
                'filter'=>Html::activeDropDownList($searchModel, 'role', $roles_array,['class'=>'form-control','prompt' => '--Select Role--']),												
                'value' => function ($model)use($roles_array){
                        return $roles_array[$model->role];
                },			
			],
			
			
            // 'id',           
            // 'auth_key',
            //'password_hash',
            //'password_reset_token',             
            // 'status',
            // 'created_at',
            // 'updated_at',
         

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
