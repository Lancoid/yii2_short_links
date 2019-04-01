<?php

use app\helpers\ShortUrlLogHelper as SULH;
use scotthuangzl\googlechart\GoogleChart;
use app\models\ShortUrl;

/* @var $this yii\web\View */
/* @var $shortUrl ShortUrl */
/* @var $details array */

$this->title = SULH::getAttributeLabel('title') . $shortUrl->short_code;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="url-details">
    <div class="row">
        <div class="col-md-6">
            <?php array_unshift($details['user_platform'], ['Name', 'Clicks']); ?>
            <?= GoogleChart::widget(
                [
                    'visualization' => 'PieChart',
                    'data' => $details['user_platform'],
                    'options' => ['title' => SULH::getAttributeLabel('user_platform')],
                ]
            ); ?>
        </div>
        <div class="col-md-6">
            <?php array_unshift($details['user_agent'], ['Name', 'Clicks']); ?>
            <?= GoogleChart::widget(
                [
                    'visualization' => 'PieChart',
                    'data' => $details['user_agent'],
                    'options' => ['title' => SULH::getAttributeLabel('user_agent')],
                ]
            ); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php array_unshift($details['user_country'], ['Country', 'Popularity']); ?>
            <?= GoogleChart::widget(
                [
                    'visualization' => 'GeoChart',
                    'data' => $details['user_country'],
                    'options' => ['dataMode' => 'regions'],
                ]
            ); ?>
        </div>
    </div>
</div>
