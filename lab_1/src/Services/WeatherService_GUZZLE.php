<?php

namespace App\Services;
use GuzzleHttp\Client;
use App\Repositories\CityRepository;
use Exception;

class WeatherService_GUZZLE {
    private $apiKey;
    private $client;

    public function __construct(protected CityRepository $cityRepository, $apiKey) {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public function get_weather_by_city_id($cityId) {
        $city = $this->cityRepository->getCityById($cityId);

        if ($city === null) {
            throw new Exception('City not found');
        }

        $url = 'https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city->getName()) . '&appid=' . $this->apiKey . '&units=metric';

        try {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (Exception $e) {
            throw new Exception('Error fetching weather data: ' . $e->getMessage());
        }
    }

}