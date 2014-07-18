<?php

/**
 * @copyright Copyright &copy; Thiago Talma, thiagomt.com, 2014
 * @package yii2-fullcalendar
 * @version 1.0.0
 */

namespace talma\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Talma FullCalendar widget is a Yii2 wrapper for the FullCalendar.
 *
 * @author Thiago Talma <thiago@thiagomt.com>
 * @since 1.0
 * @see http://arshaw.com/fullcalendar
 */
class FullCalendar extends Widget
{
    /**
     * @var array Additional config
     */
    public $config = [];

    /**
     * @var string Text for loading alert
     */
    public $loading = 'Loading...';

    /**
     * @var boolean If the plugin displays a Google Calendar.
     */
    public $googleCalendar = false;

    /**
     * @var string Hash of config options
     */
    private $_hashOptions;

    /**
     * @var string Name of the plugin
     */
    private $_pluginName = 'fullcalendar';

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->registerClientScript();

        $this->options['data-plugin-name'] = $this->_pluginName;
        $this->options['data-plugin-options'] = $this->_hashOptions;

        Html::addCssClass($this->options, 'fullcalendar');

        echo '<div id="container_' . $this->options['id'] . '">';
        echo '<div class="fc-loading" style="display: none;">' . $this->loading . '</div>';
        echo Html::tag('div', '', $this->options);
        echo '</div>';
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $options = $this->getClientOptions();
        $this->_hashOptions = $this->_pluginName . '_' . hash('crc32', serialize($options));
        $id = $this->options['id'];
        $view = $this->getView();
        $view->registerJs("var {$this->_hashOptions} = {$options};\nvar calendar_{$this->options['id']};", $view::POS_HEAD);
        $js = "calendar_{$this->options['id']} = jQuery(\"#{$id}\").fullCalendar({$this->_hashOptions});";
        $asset = FullCalendarAsset::register($view);
        if (isset($this->config['lang'])) {
            $asset->language = $this->config['lang'];
        }
        if ($this->googleCalendar) {
            $asset->googleCalendar = $this->googleCalendar;
        }
        $view->registerJs($js);
    }

    /**
     * @return array the options for the text field
     */
    protected function getClientOptions()
    {
        $id = $this->options['id'];

        $options['loading'] = new JsExpression("function(isLoading, view ) {
                $('#container_{$id}').find('.fc-loading').toggle(isLoading);
        }");

        $options = array_merge($options, $this->config);
        return Json::encode($options);
    }
}
