<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Collection as CollectionModel;

/**
 * Collection represents the model behind the search form about `app\models\Collection`.
 */
class Collection extends CollectionModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collection_id'], 'integer'],
            [['collection_name', 'AppKey'], 'safe'],
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
        $query = CollectionModel::find();

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
            'collection_id' => $this->collection_id,
        ]);

        $query->andFilterWhere(['like', 'collection_name', $this->collection_name])
            ->andFilterWhere(['like', 'AppKey', $this->AppKey]);

        return $dataProvider;
    }
}
