<?php
require_once '../app_configs/db_config.php';


$query = "SELECT * FROM garage_app.opening_hours";
$stmt = $pdo->query($query);
$openingHours = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (empty($openingHours)) {
  
  $defaultHours = [
    1 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Monday
    2 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Tuesday
    3 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Wednesday
    4 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Thursday
    5 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Friday
    6 => [
      'opening_time_am' => '09:00:00',
      'closing_time_am' => '12:00:00',
      'opening_time_pm' => '14:00:00',
      'closing_time_pm' => '18:00:00',
      'state' => 'ouvert'
    ], // Saturday
    7 => [
      'opening_time_am' => '00:00:00',
      'closing_time_am' => '00:00:00',
      'opening_time_pm' => '00:00:00',
      'closing_time_pm' => '00:00:00',
      'state' => 'ferme'
    ], // Sunday
  ];

  
  $query = "INSERT INTO garage_app.opening_hours (day_of_week, opening_time_am, closing_time_am, opening_time_pm, closing_time_pm, state) VALUES (:day_of_week, :opening_time_am, :closing_time_am, :opening_time_pm, :closing_time_pm, :state)";
  $stmt = $pdo->prepare($query);
  foreach ($defaultHours as $day => $hours) {
    $stmt->execute([
      'day_of_week' => $day,
      'opening_time_am' => $hours['opening_time_am'],
      'closing_time_am' => $hours['closing_time_am'],
      'opening_time_pm' => $hours['opening_time_pm'],
      'closing_time_pm' => $hours['closing_time_pm'],
      'state' => $hours['state']
    ]);
  }

  echo 'Default opening hours inserted successfully.';
} else {
  echo 'Opening hours already exist.';
}
?>