<?php
/**
 * Created by PhpStorm.
 * User: datrix
 * Date: 9/1/17
 * Time: 4:25 PM
 */

use Cake\Cache\Cache;

Cache::setConfig('antiflood', [
    'className' => 'Memcached',
    'duration' => '+15 minutes',
    'prefix' => 'JorisVaesen_Antiflood_',
]);
