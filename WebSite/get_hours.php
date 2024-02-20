<?php
require './app_configs/db_config.php';

$query = "SELECT day_of_week, 
                IFNULL(opening_time_am, 'Closed') AS opening_time_am, 
                IFNULL(closing_time_am, 'Closed') AS closing_time_am, 
                IFNULL(opening_time_pm, 'Closed') AS opening_time_pm, 
                IFNULL(closing_time_pm, 'Closed') AS closing_time_pm
          FROM garage_app.opening_hours";
$stmt = $pdo->query($query);
$openingHours = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($openingHours as $key => $hour) {
  $openingHours[$key]['opening_time_am'] = $hour['opening_time_am'] === 'Closed' ? 'Closed' : substr($hour['opening_time_am'], 0, 5);
  $openingHours[$key]['closing_time_am'] = $hour['closing_time_am'] === 'Closed' ? 'Closed' : substr($hour['closing_time_am'], 0, 5);
  $openingHours[$key]['opening_time_pm'] = $hour['opening_time_pm'] === 'Closed' ? 'Closed' : substr($hour['opening_time_pm'], 0, 5);
  $openingHours[$key]['closing_time_pm'] = $hour['closing_time_pm'] === 'Closed' ? 'Closed' : substr($hour['closing_time_pm'], 0, 5);
}

header('Content-Type: application/json');
echo json_encode($openingHours);
?>