<?php

use Illuminate\Support\Facades\Cache;
use App\Model\Setting;

function getData($column){
    $setting = Setting::where('name',$column)->get();
    return $setting[0]->value;
}
