<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="error">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-exclamation-triangle"></i> <big><?= $message ?></big></div>
                <div class="panel-body">
                    <p>
                        There was an error processing your request.<br>
                        Please contact <a href='mailto:<?= Yii::$app->params['contactEmail']; ?>'>us</a> if you think this is a server error. Thanks.<br>
                        You can return to the <a href='<?= Yii::$app->homeUrl; ?>'>main page</a> or try again.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
