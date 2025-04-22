<?php

namespace App\Models;

class Coord {
    private $lon;
    private $lat;

    public function __construct($lon, $lat) {
        $this->lon = $lon;
        $this->lat = $lat;
    }

    public function getLon() {
        return $this->lon;
    }

    public function getLat() {
        return $this->lat;
    }

    public function setLon($lon) {
        $this->lon = $lon;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }
}