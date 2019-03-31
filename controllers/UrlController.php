<?php

namespace app\controllers;

use yii\web\{Controller, NotFoundHttpException, Response, HttpException};
use yii\filters\{AccessControl, VerbFilter};
use app\models\{ShortUrl, ShortUrlLog};
use app\models\search\ShortUrlSearch;
use app\models\helpers\ErrorHelper;
use yii\db\StaleObjectException;
use lysenkobv\GeoIP\GeoIP;
use Throwable;
use Exception;
use Yii;

/**
 * Class UrlController
 *
 * @package app\controllers
 */
class UrlController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'add', 'forward'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'details'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
        $model = new ShortUrl();
        $searchModel = new ShortUrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model->load(Yii::$app->request->post())) {
            $model->genShortCode();
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save()) {
                        throw new Exception(ErrorHelper::getAttributeLabel('error_save_short_url'));
                    }

                    $transaction->commit();
                    $this->refresh();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new Exception($e->getMessage());
                }
            }
        }

        return $this->render('index', ['model' => $model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * @param $code
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionForward($code)
    {
        $url = ShortUrl::validateShortCode($code);
        $url->updateCounters(['counter' => 1]);

        $model = new ShortUrlLog();
        $model->setAttributes(array_merge(['short_url_id' => $url->getId()], $this->getUserDetails()));
        $model->save();

        return $this->redirect($url['long_url']);
    }

    /**
     * @param $code
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function actionDetails(string $code)
    {
        $shortUrl = ShortUrl::validateShortCode($code);

        return $this->render('details', ['shortUrl' => $shortUrl, 'details' => ShortUrl::getLogDetails($shortUrl)]);
    }

    /**
     * @param string $code
     *
     * @return Response
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete(string $code)
    {
        $shortUrl = ShortUrl::validateShortCode($code);
        $shortUrl->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return array
     */
    private function getUserDetails()
    {
        $userInfo = parse_user_agent();
        $geoip = new GeoIP();
        $userIp = $geoip->ip();

        return [
            'user_platform' => $userInfo['platform'],
            'user_agent' => $userInfo['browser'],
            'user_refer' => Yii::$app->request->referrer,
            'user_ip' => Yii::$app->request->userIP,
            'user_country' => $userIp->country,
            'user_city' => $userIp->city,
        ];
    }
}
