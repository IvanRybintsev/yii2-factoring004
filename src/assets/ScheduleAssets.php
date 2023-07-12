<?php

namespace BnplPartners\Factoring004Yii2\assets;

use yii\web\AssetBundle;

class ScheduleAssets extends AssetBundle
{

    public $js = [
        'js/factoring004-schedule.53c9ed8.js'
    ];
    public $css = [
        'css/factoring004-schedule.0ebdbe1.css'
    ];

    public function init()
    {
        $this->sourcePath = dirname(__DIR__).'/web';
        parent::init();
    }
}