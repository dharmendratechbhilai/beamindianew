<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Save/Update cache data
if (!function_exists('update_cache')) {
  function update_cache($key, $data, $ttl = NULL)
  {
    $CI = &get_instance();
    $CI->load->driver('cache', array('adapter' => 'file'));
    $CI->cache->delete($key);
    if ($ttl == NULL) {
      $ttl = CACHE_TTL_30DAYS;
    }
    $CI->cache->save($key, $data, $ttl);
    return TRUE;
  }
}

// Get cached data
if (!function_exists('get_cache')) {
  function get_cache($key)
  {
    $CI = &get_instance();
    $CI->load->driver('cache', array('adapter' => 'file'));
    return $CI->cache->get($key);
  }
}

// Clear cache by key
if (!function_exists('clear_cache')) {
  function clear_cache($key)
  {
    $CI = &get_instance();
    $CI->load->driver('cache', array('adapter' => 'file'));
    return $CI->cache->delete($key);
  }
}
