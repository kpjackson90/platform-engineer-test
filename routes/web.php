<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Production;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/show', function (Request $request) {
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ]);
    $start_date = strtotime($request->start_date);
    $end_date = strtotime($request->end_date);

    $data = file_get_contents('https://c2t-cabq-open-data.s3.amazonaws.com/film-locations-json-all-records_03-19-2020.json');
    $jsonData = json_decode($data);
    $features = $jsonData->features;
    if(!$features){
        return;
    }
    $result = (object) [
        'count' => 0,
        'production' => array()
    ];

    foreach ($features as &$feature) {
        $shootDate= $feature->attributes->ShootDate/1000;
        $title = $feature->attributes->Title;

        //filter by date
        if($shootDate < $start_date || $shootDate > $end_date){
            continue;
        }

        //add to results
        if(isset($result->production[$title])){
            $result->production[$title]->add_site($feature);
        }
        else{
            $result->count++;
            $result->production[$title] = new Production($feature);
        }
     }
    return view('show', ['data' => $result]);
});
