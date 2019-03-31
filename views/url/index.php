<?php

use app\models\helpers\{MainHelper as MH, ShortUrlHelper as SUH};
use yii\helpers\{Html, Url};
use app\models\search\ShortUrlSearch;
use app\widgets\ClipboardJsWidget;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use app\models\ShortUrl;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model ShortUrl */
/* @var $searchModel ShortUrlSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = SUH::getAttributeLabel('title');
?>

<div class="url-index">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 jumbotron">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'long_url')->input('url', ['placeholder' => SUH::getAttributeLabel('placeholder')]); ?>
            <div class="form-group">
                <?= Html::submitButton(MH::getAttributeLabel('save'), ['class' => 'btn btn-primary']); ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{pager}\n{items}",
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'columns' => [
                    [
                        'attribute' => 'short_code',
                        'label' => SUH::getAttributeLabel('short_code'),
                        'format' => 'raw',
                        'value' => function ($data)
                        {
                            return Html::a($data['short_code'], ['/' . $data['short_code']], ['target' => '_blank']);
                        },
                    ],
                    [
                        'attribute' => false,
                        'label' => false,
                        'format' => 'raw',
                        'value' => function ($data)
                        {
                            return ClipboardJsWidget::widget(
                                [
                                    'label' => '<span class="glyphicon glyphicon glyphicon glyphicon-copy" aria-hidden="true"></span>',
                                    'successText' => '<span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>',
                                    'text' => Url::to($data['short_code'], true),
                                ]
                            );
                        },
                    ],
                    [
                        'attribute' => 'long_url',
                        'label' => SUH::getAttributeLabel('long_url'),
                        'value' => function ($data)
                        {
                            return $data['long_url'];
                        },
                    ],
                    [
                        'attribute' => 'counter',
                        'label' => SUH::getAttributeLabel('counter'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function ($data)
                        {
                            return $data['counter'];
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => SUH::getAttributeLabel('created_at'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function ($data)
                        {
                            return Yii::$app->formatter->asDatetime($data['created_at']);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{delete}',
                        'contentOptions' => ['class' => 'text-center'],
                        'buttons' => [
                            'view' => function ($url, $model, $key)
                            {
                                return Html::a(
                                    '<i class="glyphicon glyphicon-search" aria-hidden="true"></i>',
                                    ['/details', 'code' => $model['short_code']],
                                    ['class' => 'label label-success grid-labels']
                                );
                            },
                            'delete' => function ($url, $model, $key)
                            {
                                return Html::a(
                                    '<i class="glyphicon glyphicon-remove" aria-hidden="true"></i>',
                                    ['/delete', 'code' => $model['short_code']],
                                    [
                                        'class' => 'label label-danger grid-labels',
                                        'data' => [
                                            'confirm' => 'Are you sure to delete this short url?',
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                            },
                        ],
                        'visible' => !Yii::$app->user->isGuest,
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
