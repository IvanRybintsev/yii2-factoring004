<?php

namespace BnplPartners\Factoring004Yii2\controllers;

use yii\web\Controller;

class ErrorController extends Controller
{
    public function actionIndex()
    {
        return $this->renderFile('@vendor/bnpl-partners/yii2-factoring004/src/views/error/index.php');
    }
}