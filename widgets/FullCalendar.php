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
    public $loading = [];
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
        if (empty($this->url)) {
            //throw new InvalidConfigException('The "url" property must be set.');
        //} elseif (empty($this->varName)) {
        //    throw new InvalidConfigException('The "varName" property must be set.');
        } elseif (empty($this->parents)) {
            //throw new InvalidConfigException('The "parents" property must be set.');
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $this->registerClientScript();
/*
        $this->options['data-plugin-name'] = $this->_pluginName;
        $this->options['data-plugin-options'] = $this->hashOptions;
        $this->options['data-chained'] = $this->attribute ?: $this->name;

        Html::addCssClass($this->options, 'form-control');

        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->bootstrap, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->bootstrap, $this->options);
        }*/


        echo Html::tag('div', '', $this->options);
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
        //$options['editable'] = true;
        $options['events'] = "/json-events.php";
        $options['defaultView'] = 'agendaWeek';
        $options['allDaySlot'] = false;
        $options['axisFormat'] = 'H:mm';
        $options['timeFormat'] = 'H:mm{ - H:mm}';
        $options['eventDrop'] = new JsExpression("function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days (should probably update your database)');
        }");

        $options['monthNames'] = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        $options['monthNamesShort'] = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        $options['dayNames'] = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        $options['dayNamesShort'] = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

        $options['buttonText'] = [
            'today' =>  'hoje',
            'month' => 'mês',
            'week' => 'semana',
            'day' => 'dia'
	    ];
        $options['header'] = [
            'left' => 'prev,next today',
            'right' => 'month,agendaWeek,agendaDay'
        ];
        $options['columnFormat'] = [
            'month' => 'ddd',
            'week' => 'ddd d/M',
            'day' => 'dddd d/M'
        ];

        $options['slotMinutes'] = 15;
        $options['snaptMinutes'] = 5;
        $options['selectable'] = true;
        $options['selectHelper'] = true;
        $options['minTime'] = 8;
        $options['maxTime'] = 19;

        $this->view->registerJs("
            var update_campos = function(start, end) {
				$('#agendamentoservico-data').val($.fullCalendar.formatDate(start, 'dd/MM/yyyy'));
				$('#agendamentoservico-horario').val($.fullCalendar.formatDate(start, 'HH:mm'));
				$('#agendamentoservico-minduracao').val((end - start)/60000);
            };
        ", View::POS_HEAD);

        $options['select'] = new JsExpression("function(start, end, allDay) {
				console.log([start, end, allDay]);
				update_campos(start, end);
			}");
        $options['eventResize'] = new JsExpression("function(event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) {
				console.log([event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view]);
			}");
        $options['dayClick'] = new JsExpression("function(date, allDay, jsEvent, view) {
				calendar_{$this->options['id']}.fullCalendar('gotoDate', date);
				$.fullCalendar.formatDate('changeView', 'agendaDay');
				return false;
			}");


        /*$options['header'] = [
        'left' => 'prev,next today',
				'center' => 'title',
				'right' => 'month,agendaWeek,agendaDay'
		];*/

        $options = array_merge($options, $this->config);
        return Json::encode($options);
    }
}
