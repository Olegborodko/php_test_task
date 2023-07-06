<?php
/**
 * @charset UTF-8
 *
 * Є список часових інтервалів (інтервали записані у форматі гг:хх-гг:хх).
 *
 * Необхідно написати дві функції:
 *
 *
 * Перша функція повинна перевіряти часовий інтервал на валідність
 * 	прийматиме вона один параметр: часовий інтервал (рядок у форматі гг:хх-гг:хх)
 * 	повертати boolean
 *
 *
 * Друга функція повинна перевіряти "накладання інтервалів" при спробі додати новий інтервал до списку існуючих
 * 	прийматиме вона один параметр: часовий інтервал (рядок у форматі гг:хх-гг:хх)
 *  повертати boolean
 *  якщо boolean == false, додавати інтервал до існуючого списку
 *
 *  "накладання інтервалів" - це коли в проміжку між початком та закінченням одного інтервалу,
 *   зустрічається початок, закінчення або те й інше одночасно, іншого інтервалу
 *
 *  приклад:
 *
 *  є інтервали
 *  	"10:00-14:00"
 *  	"16:00-20:00"
 *
 *  намагаємося додати ще один інтервал
 *  	"09:00-11:00" => відбулося накладання
 *  	"11:00-13:00" => відбулося накладання
 *  	"14:00-16:00" => НЕ відбулося накладання, інтервал додано до списку
 *  	"14:00-17:00" => відбулося накладання
 *
 * Вхідні данні:
 */

$list = [
  '09:00-11:00',
  '11:00-13:00',
  '15:00-16:00',
  '17:00-20:00',
  '20:30-21:30',
  '21:30-22:30',
];

//Тут ваш код...
error_reporting(E_ALL);
ini_set('display_errors', '1');

function validateTimeInterval($interval)
{
  $times = explode('-', $interval);

  if (count($times) !== 2) {
    return false;
  }

  $startTime = DateTime::createFromFormat('H:i', trim($times[0]));
  $endTime = DateTime::createFromFormat('H:i', trim($times[1]));

  if (!$startTime || !$endTime) {
    return false;
  }

  if ($startTime >= $endTime) {
    return false;
  }

  return true;
}

function intervalOverlap($newInterval)
{
  if (!validateTimeInterval($newInterval)) {
    echo 'time is not valid';
    return true;
  }

  $times = explode('-', $newInterval);

  $startTime = strtotime(trim($times[0]));
  $endTime = strtotime(trim($times[1]));

  global $list;
  foreach ($list as $interval) {
    $intervalTimes = explode('-', $interval);

    $existingStartTime = strtotime(trim($intervalTimes[0]));
    $existingEndTime = strtotime(trim($intervalTimes[1]));

    if ($endTime > $existingStartTime && $endTime <= $existingEndTime) {
      return true;
    }

    if ($startTime <= $existingStartTime && $endTime >= $existingEndTime) {
      return true;
    }

    if ($startTime >= $existingStartTime && $startTime < $existingEndTime) {
      return true;
    }

    if ($startTime >= $existingStartTime && $endTime <= $existingEndTime) {
      return true;
    }
  }

  $list[] = $newInterval;

  return false;
}

// test
$result = validateTimeInterval("14:00-16:00");
echo $result ? "true" : "false";
echo "<br/>";

$result = validateTimeInterval("13:00-13:00");
echo $result ? "true" : "false";
echo "<br/>";

$result = intervalOverlap("09:00-11:30");
echo $result ? "true" : "false";
echo "<br/>";

$result = intervalOverlap("14:00-17:00");
echo $result ? "true" : "false";
echo "<br/>";

$result = intervalOverlap("14:00-15:00");
echo $result ? "true" : "false";
echo "<br/>";

$result = intervalOverlap("08:00-09:00");
echo $result ? "true" : "false";
echo "<br/>";

$result = intervalOverlap("22:30-22:31");
echo $result ? "true" : "false";
echo "<br/>";

echo "<pre>";
print_r($list);
echo "</pre>";
