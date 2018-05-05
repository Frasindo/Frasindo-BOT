<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ardor extends CI_Model{

  public $curl;
  public $ardor = "http://185.82.22.247:27876/nxt";
  public function __construct()
  {
    parent::__construct();
    $this->load->library('curl_lib');
    $this->curl = $this->curl_lib;
  }
  public function get($requestType="",$data=[])
  {
    $build = ["requestType"=>$requestType];
    foreach ($data as $key => $value) {
      $build[$key] = $value;
    }
    $res = $this->curl->get($this->ardor,$build);
    if($res != false){
        return $res = json_decode($res->body);
    }else{
        return false;
    }
  }
  public function post($requestType="",$data=[])
  {
    $res = $this->curl->post($this->ardor."?requestType=".$requestType,$data);
    if($res != false){
        return $res = json_decode($res->body);
    }else{
        return false;
    }
  }

}
