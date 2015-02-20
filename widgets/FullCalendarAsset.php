<?php

/**
 * @copyright Copyright &copy; Thiago Talma, thiagomt.com, 2014
 * @package yii2-fullcalendar-widget
 * @version 1.0.0
 */

namespace talma\widgets;

use yii\web\AssetBundle;
use Yii;

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
    public $depends = ['yii\jui\JuiAsset'];

    /**
     * @inheritdoc
     */
    public $js = ['lib/moment.min.js', 'fullcalendar.js'];

    /**
     * @var string App language
     */
    public $language;

    /**
     * @var boolean If the plugin displays a Google calendar.
     */
    public $googleCalendar = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css = ['fullcalendar.css'];
        $this->sourcePath = __DIR__ . '/../assets';
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        $language = strtolower($this->language ? $this->language : Yii::$app->language);
        
        if ($language != 'en-us') {
            $this->js[] = "lang/{$language}.js";
        }

        if ($this->googleCalendar) {
            $this->js[] = "gcal.js";
        }

        $view->registerCssFile($this->baseUrl . '/' . 'fullcalendar.print.css', ['media' => 'print']);

        parent::registerAssetFiles($view);
    }
}
