<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function dateDiff($date)
{
    // Create DateTime objects
    $now = new DateTime();
    $mydate = DateTime::createFromFormat('Y-m-d H:i:s', $date);

    // If the date is not valid, return an error message
    if (!$mydate) {
        return 'Invalid date format';
    }

    // Calculate the difference between now and the given date
    $interval = $now->diff($mydate);

    // Return the largest time difference unit
    if ($interval->y > 0) {
        return $interval->y . " Years";
    } elseif ($interval->m > 0) {
        return $interval->m . " Months";
    } elseif ($interval->d > 0) {
        return $interval->d . " Days";
    } elseif ($interval->h > 0) {
        return $interval->h . " Hours";
    } elseif ($interval->i > 0) {
        return $interval->i . " Minutes";
    } else {
        return $interval->s . " Seconds";
    }
}
