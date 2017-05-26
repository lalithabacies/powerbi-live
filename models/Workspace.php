<?php

namespace app\models;

use Yii;
use app\models\Collection;

/**
 * This is the model class for table "workspaces".
 *
 * @property integer $w_id
 * @property string $workspace_name
 * @property string $workspace_id
 * @property integer $collection_id
 *
 * @property Datasets[] $datasets
 * @property Reports[] $reports
 * @property Collections $collection
 */
class Workspace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'workspaces';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workspace_name', 'workspace_id'], 'string'],
            [['collection_id'], 'integer'],
           // [['collection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collection::className(), 'targetAttribute' => ['collection_id' => 'collection_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'w_id' => 'W ID',
            'workspace_name' => 'Workspace Name',
            'workspace_id' => 'Workspace ID',
            'collection_id' => 'Collection ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatasets()
    {
        return $this->hasMany(Dataset::className(), ['workspace_id' => 'w_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::className(), ['workspace_id' => 'w_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['collection_id' => 'collection_id']);
    }
	
	/**
	* @Uses the POST/PATCH method which as parameter passing.
	* @returns the response of the URL
	*/
	
	public function doCurl_POST($end_url,$access_key,$params,$content_type,$method)
	{
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $end_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => array(
            "authorization: AppKey ".$access_key,
            "content-type: {$content_type}; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
          ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }
	
	/**
	* @Uses the GET method
	* @returns the response of the URL
	*/
	public function doCurl_GET($end_url,$access_key){
		
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $end_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "authorization: AppKey ".$access_key,
          ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }
	
	/**
	* @Uses the DELETE as the request method
	* @returns the response of the URL
	*/
	public function doCurl_DELETE($end_url,$access_key){
		
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $end_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "DELETE",
          CURLOPT_HTTPHEADER => array(
            "authorization: AppKey ".$access_key,
          ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }
	
}
