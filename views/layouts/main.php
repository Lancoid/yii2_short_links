<?php

use yii\bootstrap\{Nav, NavBar};
use yii\widgets\Breadcrumbs;
use app\helpers\MainHelper;
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php Yii::$app->view->registerLinkTag(['rel' => 'shortcut icon', 'href' => '/favicon.ico', 'type' => 'image/x-icon']); ?>
    <?php Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => 'description']); ?>
    <?php Yii::$app->view->registerMetaTag(['name' => 'keywords', 'content' => 'keywords']); ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
    ]); ?>
    <?= Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                [
                    'label' => MainHelper::getAttributeLabel('login'),
                    'url' => ['/login'],
                    'visible' => Yii::$app->user->isGuest,
                ],
                [
                    'label' => MainHelper::getAttributeLabel('logout'),
                    'url' => '/logout',
                    'visible' => !Yii::$app->user->isGuest,
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ],
        ]
    ); ?>
    <?php NavBar::end(); ?>
    <div class="container">
        <div class="content-wrapper">
            <section class="content-header">
                <?php if (Yii::$app->params['title'] && $this->title !== null) { ?>
                    <h1 class="page-heading"><?= Html::encode($this->title); ?></h1>
                <?php } ?>
                <?php if (Yii::$app->params['breadcrumbs']) { ?>
                    <?= Breadcrumbs::widget(
                        [
                            'homeLink' => ['label' => Yii::$app->name, 'url' => Yii::$app->homeUrl],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]
                    ) ?>
                <?php } ?>
            </section>
            <section class="content">
                <?= Alert::widget() ?>
                <?= $content ?>
            </section>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
