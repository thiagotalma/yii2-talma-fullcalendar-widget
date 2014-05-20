<?php

/**
 * @copyright Copyright &copy; Thiago Talma, thiagomt.com, 2014
 * @package yii2-fullcalendar-widget
 * @version 1.0.0
 */

namespace talma\widgets;

use yii\web\AssetBundle;

/**
 * Asset bundle for FullCalendar
 *
 * @author Thiago Talma <thiago@thiagomt.com>
 * @since 1.0
 */
class FullCalendarAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->js = ['fullcalendar.js', 'jquery-ui.custom.min.js'];
        $this->css = ['fullcalendar.css'];
        $this->sourcePath = __DIR__ . '/../assets';
        parent::init();
    }
}
