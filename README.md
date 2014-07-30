yii2-talma-fullcalendar-widget
===========
Widget for Yii Framework 2.0 to use [FullCalendar](http://arshaw.com/fullcalendar)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist thiagotalma/yii2-fullcalendar "*"
```

or add

```
"thiagotalma/yii2-fullcalendar": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by :

```php
<?= \talma\widgets\FullCalendar::widget([
    'googleCalendar' => true,  // If the plugin displays a Google Calendar. Default false
    'loading' => 'Carregando...', // Text for loading alert. Default 'Loading...'
    'config' => [
        // put your options and callbacks here
        // see http://arshaw.com/fullcalendar/docs/
        'lang' => 'pt-br', // optional, if empty get app language
        ...
    ],
]); ?>
```