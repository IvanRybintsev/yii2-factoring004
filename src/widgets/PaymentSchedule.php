<?php

namespace BnplPartners\Factoring004Yii2\widgets;

use BnplPartners\Factoring004Yii2\assets\ScheduleAssets;
use yii\base\Widget;

class PaymentSchedule extends Widget
{
    public $amount = 0;
    public $styles = '';
    public $blockId = 'factoring004-schedule';

    public function init()
    {
        parent::init();
        ScheduleAssets::register($this->getView());
    }

    public function run()
    {
        parent::run();
        if ($this->amount > 0) {
            return $this->render('schedule',['amount' => $this->amount, 'styles' => $this->styles, 'blockId' => $this->blockId]);
        }
        return '';
    }
}