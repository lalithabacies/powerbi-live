<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Customer;
use app\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */

	  
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

	/**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	
    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
			
			$password= $model->generateUniqueRandomString("username");
			$model->setPassword($password);
            $model->generateAuthKey();
			if($model->save())
			{
			    $customer = new Customer();
				$customer->eq_customer_id = $model->id;
				$customer->created_at = date("Y-m-d H:i:s");  
				$customer->save(); 
				
				$model->sendEmailAddUser($model->id,$password);	
			    Yii::$app->session->setFlash('user_create_success');
				return $this->redirect(['index']);
			}
        } 
        return $this->render('create', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $customer = Customer::find()->where(['eq_customer_id'=>$id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$customer->updated_at = date("Y-m-d H:i:s");  			
			$customer->save(); 
				
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
 		$customer = Customer::find()->where(['eq_customer_id'=>$id])->one();
		$customer->delete(); 
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionGetApiAllUser(){
	
		 
	 
		$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://localhost/powerbi/web/index.php/samples",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
				"authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB",
				"cache-control: no-cache",
				"postman-token: 2da8ae06-80a7-2204-3e27-1b3ba00f6ae4"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			}
 
    }
	
	
	public function actionGetApiSingleUser(){
			 	 
		$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://localhost/powerbi/web/index.php/samples/2",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
				"authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB",
				"cache-control: no-cache",
				"postman-token: 2da8ae06-80a7-2204-3e27-1b3ba00f6ae4"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			}
 
    }

	public function actionInsertApiSingleUser(){
		
		$curl = curl_init();

				curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://localhost/powerbi/web/index.php/samples",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "username=154211512&email=testeed%4022gmail.c.om&auth_key=Asfsdf-ewekwekwkeowkew%2Csdspd&password_hash=dasdasdaasdasdaseqw&created_at=12312312123123&updated_at=14141414141414114141",
			  CURLOPT_HTTPHEADER => array(
				"authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB",
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded",
				"postman-token: eda6481f-4e4c-4bb9-2665-e3a9a7247431"
			  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
				  echo $response;
				}
	}
	
		
	public function actionDeleteApiSingleUser(){
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://localhost/powerbi/web/index.php/samples/35",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "DELETE",
		  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"client_id\"\r\n\r\n789789\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB",
			"cache-control: no-cache",
			"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
			"postman-token: 14dd3fed-635b-baf2-f6bd-ecb42101613c"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}
	

	public function actionUpdateApiSingleUser(){
		
			$curl = curl_init();
		  curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://localhost/powerbi/web/index.php/samples/32",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "PATCH",
		  CURLOPT_POSTFIELDS => "client_id=1414141",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB",
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			"postman-token: 99905b8a-0c91-a6e8-c2b4-1fb8ed559ac5"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
    }
	
}
