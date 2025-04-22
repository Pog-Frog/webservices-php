<?php

namespace App\Repositories;
use App\Models\City;
use App\Models\Coord;

class CityRepository {
    private $cities = [];

    public function __construct() {
        $this->load_cities();
    }

    private function load_cities() {
        $temp = json_decode(file_get_contents(DATA_FILE_URL), true);

        if ($temp === null) {
            return null;
        }

        foreach ($temp as $city_data) {
            $coord = new Coord($city_data['coord']['lon'], $city_data['coord']['lat']);
            $city = new City($city_data['id'], $city_data['name'], $city_data['country'], $coord);
            $this->cities[$city_data['id']] = $city;
        }
    }

    public function getCityById($id) {
        if (isset($this->cities[$id])) {
            return $this->cities[$id];
        } 
        
        return null;
    }

    public function get_all_cities() {
        return $this->cities;
    }
}