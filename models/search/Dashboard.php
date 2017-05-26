<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dashboard as DashboardModel;

/**
 * Dashboard represents the model behind the search form about `app\models\Dashboard`.
 */
class Dashboard extends DashboardModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dashboard_id', 'workspace_id'], 'integer'],
            [['dashboard_name', 'pbix_file', 'description', 'models', 'report_id', 'form_data'], 'safe'],
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
        $query = DashboardModel::find();

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
            'dashboard_id' => $this->dashboard_id,
            'workspace_id' => $this->workspace_id,
        ]);

        $query->andFilterWhere(['like', 'dashboard_name', $this->dashboard_name])
            ->andFilterWhere(['like', 'pbix_file', $this->pbix_file])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'models', $this->models])
            ->andFilterWhere(['like', 'report_id', $this->report_id])
            ->andFilterWhere(['like', 'form_data', $this->form_data]);

        return $dataProvider;
    }
}
