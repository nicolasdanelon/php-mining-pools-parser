<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => __DIR__ . '/database.sqlite',
    'prefix'   => '',
]);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();

//Capsule::schema()->drop('cryptos');
try {
    Capsule::schema()->create('cryptos', function ($table) {
        $table->increments('id');
        $table->string('coin');
        $table->string('pool');
        $table->timestamps();
    });
} catch (\Exception $ex) {

}

$capsule->bootEloquent();

$all = Capsule::table('cryptos')
    ->select('*')
    ->where('pool', '=', 'zpool')
    ->where('coin', '=', '0.00252236')
    ->where('created_at', '>=', \Carbon\Carbon::today())
    ->get()
    ->count();

echo '<pre>';
print_r($all);

die();
