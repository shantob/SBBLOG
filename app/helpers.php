<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


if (!function_exists('file_upload')) {
    function file_upload($file, $folder) {
        $filename = time(). rand(100, 999).'.'. $file->getClientOriginalExtension();
        $file->storeAs('public/'.$folder, $filename);

        return $folder.'/'.$filename;
    }
}

if (!function_exists('file_delete')) {
    function file_delete($file) {
        $file = 'storage/'.$file;
        if(File::exists($file)){
            File::delete($file);
        }
    }
}

if (!function_exists('file_path')) {
    function file_path($file=null) {
        if($file == null) {
            $file =  'images/default.jpg';
        }

        return url('/storage/'.$file);
    }
}



if(!function_exists('hour_difference')){
    function hour_difference($startTime, $exitTime){
        $time1 = new DateTime($startTime);
        $time2 = new DateTime($exitTime);
        $interval = $time1->diff($time2);

        $response['hour'] = $interval->format('%h');
        $response['min'] = $interval->format('%i');
        $response['hour_min'] = $interval->format('%h.%i');
        
        return $response;
    }
}
