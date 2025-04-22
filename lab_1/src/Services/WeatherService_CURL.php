<?php

namespace App\Services;
use App\Repositories\CityRepository;
use Exception;

class WeatherService_CURL {
    private $api_key;
    private $base_url;
    
    public function __construct(protected CityRepository $cityRepository, $api_key) {
        $this->api_key = $api_key;
        $this->base_url = 'https://api.openweathermap.org/data/2.5/weather';
    }

    public function get_weather_by_city_id($city_id) {
        $city = $this->cityRepository->getCityById($city_id);

        if ($city === null) {
            throw new Exception('City not found');
        }

        $url = $this->base_url . '?q=' . urlencode($city->getName()) . '&appid=' . $this->api_key . '&units=metric';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            throw new Exception('Error fetching weather data');
        }
        
        return json_decode($response, true);
    }
}