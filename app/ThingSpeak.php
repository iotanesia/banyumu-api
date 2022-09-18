<?php

namespace App;

use berkas1\thingspeak_php\Api As ThingSpeakApi;


class ThingSpeak {
    public function connThingSpeak() {
        try {
            $thinkSpeak = new ThingSpeakApi('2313','12312');
            


        } catch (\Throwable $th) {
            throw $th;
        }
    }

}