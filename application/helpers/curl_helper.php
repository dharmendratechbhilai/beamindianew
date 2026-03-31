<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('callAPI')) {
  function callAPI($method, $url, $data = false, $headers = [])
  {
    $curl = curl_init();
    $method = strtoupper($method);

    // Handle GET query string
    if ($method === 'GET' && !empty($data) && (is_array($data) || is_object($data))) {
      $url .= '?' . http_build_query($data);
    }

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    switch ($method) {
      case "POST":
        curl_setopt($curl, CURLOPT_POST, true);
        if (!empty($data)) {
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        break;

      case "PUT":
      case "PATCH":
      case "DELETE":
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        break;
    }

    // Set headers
    $defaultHeaders = ['Content-Type: application/json'];
    if (!empty($headers)) {
      $defaultHeaders = array_merge($defaultHeaders, $headers);
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $defaultHeaders);

    // Execute and handle errors
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
      return ['status' => false, 'error' => $error];
    }
    return ['status' => true, 'data' => json_decode($response, true)];
  }
}
