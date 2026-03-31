<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Location\Coordinate;
use Location\Distance\Vincenty;

if (!function_exists('checkEmpWithinRadius')) {

  function checkEmpWithinRadius($empLat, $empLng, array $conditions, $radius_m = 10)
  {
    if (empty($empLat) || empty($empLng)) {
      return [
        "status" => false,
        "message" => "Invalid latitude or longitude.",
        "data" => [
          "within_radius" => false,
          "work_location" => null,
          "work_location_id" => null,
          "distance_meters" => null
        ]
      ];
    }

    if (!isset($conditions['comp_uid'])) {
      return [
        "status" => false,
        "message" => "company uid missing in conditions.",
        "data" => [
          "within_radius" => false,
          "work_location" => null,
          "work_location_id" => null,
          "distance_meters" => null
        ]
      ];
    }

    $CI = &get_instance();
    $CI->load->database();

    $CI->db->where('comp_uid', $conditions['comp_uid']);
    $workLocations = $CI->db->get('companies_work_location')->result_array();

    if (empty($workLocations)) {
      return [
        "status" => false,
        "message" => "Work location not found.",
        "data" => [
          "within_radius" => false,
          "work_location" => null,
          "work_location_id" => null,
          "distance_meters" => null
        ]
      ];
    }

    $reference = new Coordinate($empLat, $empLng);
    $calculator = new Vincenty();

    $isWithinRange = false;
    $minDistance = PHP_INT_MAX;  // store smallest distance
    $location_name = null;
    $work_location_id = null;
    foreach ($workLocations as $workArea) {

      if (!empty($workArea['latitude']) && !empty($workArea['longitude'])) {
        $workCoord = new Coordinate($workArea['latitude'], $workArea['longitude']);
        $currentDistance = $calculator->getDistance($reference, $workCoord);

        $locationAllowedRadius = $workArea['allowed_radius'];

        // track nearest distance
        if ($currentDistance < $minDistance) {
          $minDistance = $currentDistance;
        }
        // check radius
        if ($currentDistance <= $locationAllowedRadius) {
          $isWithinRange = true;
          $location_name = $workArea['location'];
          $work_location_id = $workArea['id'];
          break;
        }
      }
    }
    return [
      "status" => true,
      "message" => $isWithinRange ? "Employee is within radius." : "Employee is outside radius.",
      "data" => [
        "within_radius" => $isWithinRange,
        "work_location" => $location_name,
        "work_location_id" => $work_location_id,
        "distance_meters" => $minDistance   // ALWAYS return nearest distance
      ]
    ];
  }
}
