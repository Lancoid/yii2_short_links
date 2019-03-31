<?php

namespace app\models\search;

use yii\data\ActiveDataProvider;
use app\models\ShortUrl;
use yii\base\Model;

/**
 * Class ShortUrlSearch
 *
 * @package app\models\search
 */
class ShortUrlSearch extends ShortUrl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['long_url', 'string', 'max' => 255],
            ['short_code', 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ShortUrl::find();
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 15]]);

        $dataProvider->sort->attributes['short_code'] =
            [
                'asc' => ['short_code' => SORT_ASC],
                'desc' => ['short_code' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['long_url'] =
            [
                'asc' => ['long_url' => SORT_ASC],
                'desc' => ['long_url' => SORT_DESC],
            ];
        $dataProvider->sort->defaultOrder = [
            'created_at' => SORT_DESC,
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'LOWER(short_code)', mb_strtolower($this->short_code)]);
        $query->andFilterWhere(['like', 'LOWER(long_url)', mb_strtolower($this->long_url)]);

        return $dataProvider;
    }
}
