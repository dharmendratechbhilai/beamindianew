<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Root_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  // Count All
  public function countRows($table_name)
  {
    return $this->db->count_all($table_name);
  }

  // Count All >> specific condition
  public function countSpecificRows($col, $data, $table_name)
  {
    $this->db->where($col, $data);
    return $this->db->count_all_results($table_name);
  }

  // Insert data
  public function insert($table, $data)
  {
    $this->db->query("SET time_zone = '+05:30'");
    $this->db->insert($table, $data);
    if ($this->db->affected_rows() > 0) {
      return TRUE;
    }
    return FALSE;
  }

  // Fetch all
  public function fetchAll($table)
  {
    return $this->db->get($table)->result_array();
  }

  // Fetch multiple >> limit
  public function fetchWithLimit($limit, $table)
  {
    $this->db->limit($limit);
    $query = $this->db->get($table);
    return $query->result_array();
  }

  // Fetch single >> On specific condition
  public function fetchSingle($data, $col, $table)
  {
    $this->db->where($col, $data);
    $query = $this->db->get($table);
    return ($query->num_rows() == 1) ? $query->row_array() : FALSE;
  }

  // Fetch multiple >> On specific condition
  public function fetchSpecific($data, $col, $table)
  {
    $this->db->where($col, $data);
    return $this->db->get($table)->result_array();
  }

  // Fetch Multiple >> On multiple conditions
  public function fetchSpecificMultipleConditions(array $conditions, $table)
  {
    $this->db->where($conditions);
    return $this->db->get($table)->result_array();
  }

  // Fetch rows >> On specific condition >> limit
  public function fetchSpecificWithLimit($data, $col, $table, $limit)
  {
    $this->db->where($col, $data);
    $this->db->limit($limit);
    $query = $this->db->get($table);
    return $query->result_array();
  }

  // Fetch single >> On multiple conditions
  public function fetchSingleMultipleConditions(array $conditions, $table, $select = '*')
  {
    $this->db->select($select);
    $this->db->where($conditions);
    $query = $this->db->get($table);
    return ($query->num_rows() == 1) ? $query->row_array() : FALSE;
  }

  public function fetch_CompanyLat(array $conditions, $table, $select = '*')
  {
    $this->db->select($select);
    //$this->db->where($conditions);
    //$this->db->where('id', 3);
    $query = $this->db->get($table)->result();

    print_r($query);
    //return ($query->num_rows() == 1) ? $query->row_array() : FALSE;
  }



  // Update
  public function update(array $conditions, $table, $data)
  {
    $this->db->query("SET time_zone = '+05:30'");
    $this->db->where($conditions);
    $res =  $this->db->update($table, $data); // run only once

    //echo $this->db->last_query();
    return $res;
  }

  // Delete
  public function delete($key, $col, $table)
  {
    $this->db->where($col, $key);
    $this->db->delete($table);
    return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
  }
}
