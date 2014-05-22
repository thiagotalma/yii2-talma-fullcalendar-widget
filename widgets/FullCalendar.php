<?php

/**
 * @copyright Copyright &copy; Thiago Talma, thiagomt.com, 2014
 * @package yii2-fullcalendar
 * @version 1.0.0
 */

namespace talma\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

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

    public $url;
    public $varName;
    public $parents = [];
    public $depends = [];
    public $bootstrap = [];
    public $avoidEmpty = true;
    public $loading = 'Loading...';
    public $clearChildren = true;

    /**
     * @var string Hash of plugin options
     */
    public $hashOptions;

    private $_pluginName = 'fullcalendar';


    /**
     * Initializes the widget.
     * @throws InvalidConfigException if the "mask" property is not set.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->registerClientScript();

        $this->options['data-plugin-name'] = $this->_pluginName;
        $this->options['data-plugin-options'] = $this->hashOptions;

        Html::addCssClass($this->options, 'fullcalendar');

        echo '<div id="container_' . $this->options['id'] . '">';
        echo '<div class="fullcalendar-loading" style="display: none;">' . $this->loading . '</div>';
        echo Html::tag('div', '', $this->options);
        echo '</div>';
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $options = $this->getClientOptions();
        $this->hashOptions = $this->_pluginName . '_' . hash('crc32', serialize($options));
        $id = $this->options['id'];
        $view = $this->getView();
        $view->registerJs("var {$this->hashOptions} = {$options};\nvar calendar_{$this->options['id']};", $view::POS_HEAD);
        $js = "calendar_{$this->options['id']} = jQuery(\"#{$id}\").fullCalendar({$this->hashOptions});";
        FullCalendarAsset::register($view);
        $view->registerJs($js);
    }

    /**
     * @return array the options for the text field
     */
    protected function getClientOptions()
    {
        $id = $this->options['id'];

        //$options['editable'] = true;
        //$options['events'] = "/agenda/json/10";
        //$options['eventSources'] = ["/json-events.php"];
        /*$options['eventSources'] = [
            ['url' => '/json-events.php', 'color' => 'green'],
            ['url' => '/json-events2.php', 'color' => 'orange']
        ];*/
        $options['header'] = [
            'left' => 'prev,next today',
            'right' => 'month,agendaWeek,agendaDay',
            'center' => 'title'
        ];
        $options['titleFormat'] = [
            'month' => 'MMMM - YYYY',
            'week' => 'MMMM - YYYY',
            'day' => 'd [de] MMMM - YYYY'
        ];
        $options['defaultView'] = 'agendaWeek';
        $options['allDaySlot'] = false;
        //$options['allDayDefault'] = false;
        $options['slotDuration'] = '00:15:00';
        $options['snaptDuration'] = '00:05:00';
        $options['axisFormat'] = 'H:mm';
        $options['selectable'] = [
            'month' => false,
            'default' => true
        ];
        //$options['unselectAuto'] = false;
        $options['unselectCancel'] = 'body';
        $options['selectHelper'] = true;
        $options['scrollTime'] = "08:00:00";
        //$options['minTime'] = "08:00:00";
        //$options['maxTime'] = "19:00:00";
        $options['defaultTimedEventDuration'] = "00:15:00";

        $options['dayClick'] = new JsExpression("function(date, jsEvent, view) {
            if (view.name == 'month') {
                calendar_{$id}.fullCalendar('gotoDate', date);
                calendar_{$id}.fullCalendar('changeView', 'agendaWeek');
            }
        }");

        $options['eventMouseover'] = new JsExpression("function(event, jsEvent, view) {
            //console.log([this, event, jsEvent, view]);
            //$(this).popover('show', {trigger: 'manual', content: 'ttt'});
        }");

        $options['select'] = new JsExpression("function(start, end, jsEvent, view) {
				if (jsEvent) {
				    calendar_{$id}.trigger('fullcalendar.select', [start, end]);
                }
        }");

        $options['eventRender'] = new JsExpression("function(event, element) {
                element.popover({
                    content: event.title,
                    trigger: 'hover'
                });
        }");

        $options['loading'] = new JsExpression("function(isLoading, view ) {
                $('#container_{$id}').find('.fullcalendar-loading').toggle(isLoading);
        }");

        $options = array_merge($options, $this->config);
        return Json::encode($options);
    }
}
