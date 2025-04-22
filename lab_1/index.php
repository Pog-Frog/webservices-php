<?php
require __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/config.php";

use App\Repositories\CityRepository;
use App\Services\WeatherService_CURL;
use App\Services\WeatherService_GUZZLE;

$cities = [];
$weatherData = null;

$cityRepository = new CityRepository();
$weatherService_curl = new WeatherService_CURL($cityRepository, API_KEY);
$weatherService_guzzle = new WeatherService_GUZZLE($cityRepository, API_KEY);

$cities = $cityRepository->get_all_cities();

$selectedCityId = isset($_GET['city_id']) ? (int)$_GET['city_id'] : null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egyptian Cities Weather Forecast</title>
</head>

<body>

    <h1>Select a City</h1>

    <?php
    if (!empty($cities)):
    ?>
        <form method="GET" action="">
            <label for="city-select">City:</label>
            <select name="city_id" id="city-select">
                <option value="">-- Select a City --</option>
                <?php
                foreach ($cities as $cityId => $city) {
                    $selectedAttribute = ($city->getId() == $selectedCityId) ? 'selected' : '';
                    
                    echo "<option value=" . $city->getId() . " $selectedAttribute>" . $city->getName() . "</option>";
                }
                ?>
            </select>
            <button type="submit">Show Info (Example)</button>
        </form>

        <?php
        if ($selectedCityId !== null && isset($cities[$selectedCityId])) {
            echo "<div class='info'>You selected City ID: " . $selectedCityId . " (Name: " . $cities[$selectedCityId]->getName() . ")</div>";
            $weatherData = $weatherService_curl->get_weather_by_city_id($selectedCityId);
            // $weatherData = $weatherService_guzzle->get_weather_by_city_id($selectedCityId);
            ?>
            <div class="weather-info">
            
            <h2>Weather for <?php echo $cities[$selectedCityId]->getName(); ?>:</h2>

            <?php if (isset($weatherData['main']['temp'])): ?>
                <p><strong>Temperature:</strong> <?= $weatherData['main']['temp'] ?> °C</p>
            <?php endif; ?>

            <?php if (isset($weatherData['main']['feels_like'])): ?>
                <p><strong>Feels Like:</strong> <?= $weatherData['main']['feels_like'] ?> °C</p>
            <?php endif; ?>

            <?php if (isset($weatherData['main']['humidity'])): ?>
                <p><strong>Humidity:</strong> <?= $weatherData['main']['humidity'] ?>%</p>
            <?php endif; ?>

            <?php if (isset($weatherData['main']['pressure'])): ?>
                <p><strong>Pressure:</strong> <?= $weatherData['main']['pressure'] ?> hPa</p>
            <?php endif; ?>

            <?php if (isset($weatherData['wind']['speed'])): ?>
                <p><strong>Wind Speed:</strong> <?= $weatherData['wind']['speed'] ?> m/s</p>
            <?php endif; ?>

            <?php if (isset($weatherData['wind']['deg'])): ?>
                <p><strong>Wind Direction:</strong> <?= $weatherData['wind']['deg'] ?>°</p>
            <?php endif; ?>

            <?php if (isset($weatherData['weather'][0]['description'])): ?>
                 <p><strong>Description:</strong> <?= $weatherData['weather'][0]['description'] ?></p>
            <?php endif; ?>
        </div>

        <?php
        } elseif ($selectedCityId !== null) {
            echo "<div class='error'>Selected city ID " . $selectedCityId . " not found in the list.</div>";
        }
        ?>

    <?php
    else:
    ?>
        <div>No cities found in the data source.</div>
    <?php
    endif;
    ?>

</body>

</html>