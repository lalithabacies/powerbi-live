<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reports;

/**
 * ReportsSearch represents the model behind the search form about `app\models\Reports`.
 */
class ReportsSearch extends Reports
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_id', 'workspace_id'], 'integer'],
            [['report_guid', 'report_name', 'web_url', 'embed_url'], 'safe'],
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
        $query = Reports::find();

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
            'r_id' => $this->r_id,
            'workspace_id' => $this->workspace_id,
        ]);

        $query->andFilterWhere(['like', 'report_guid', $this->report_guid])
            ->andFilterWhere(['like', 'report_name', $this->report_name])
            ->andFilterWhere(['like', 'web_url', $this->web_url])
            ->andFilterWhere(['like', 'embed_url', $this->embed_url]);

        return $dataProvider;
    }
}
