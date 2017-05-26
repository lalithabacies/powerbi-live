<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dataset as DatasetModel;

/**
 * Dataset represents the model behind the search form about `app\models\Dataset`.
 */
class Dataset extends DatasetModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['s_id', 'workspace_id'], 'integer'],
            [['dataset_name', 'dataset_id', 'datasource_id', 'gateway_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DatasetModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            's_id' => $this->s_id,
            'workspace_id' => $this->workspace_id,
        ]);

        $query->andFilterWhere(['like', 'dataset_name', $this->dataset_name])
            ->andFilterWhere(['like', 'dataset_id', $this->dataset_id])
            ->andFilterWhere(['like', 'datasource_id', $this->datasource_id])
            ->andFilterWhere(['like', 'gateway_id', $this->gateway_id]);

        return $dataProvider;
    }
}
