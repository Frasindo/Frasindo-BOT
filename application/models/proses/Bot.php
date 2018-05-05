<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Model{
  public $frasid;
  public function __construct()
  {
    parent::__construct();
    $this->load->model("proses/ardor");
    $this->frasid = "4777913785555377445";
  }
  public function ardortrade($type='',$qty='',$rate='',$secret="")
  {
    if ($type =='askbuy') {
      $fee = $this->ardor->post("placeBidOrder",["chain"=>2,"asset"=>$this->frasid,"quantityQNT"=>$qty*100000000,"priceNQTPerShare"=>$rate*100000000,"secretPhrase"=>$secret]);
      if (isset($fee->transactionJSON->feeNQT)) {
        $fee = $fee->transactionJSON->feeNQT;
      }else {
        return false;
      }
      $res = $this->ardor->post("placeBidOrder",["chain"=>2,"asset"=>$this->frasid,"quantityQNT"=>$qty*100000000,"priceNQTPerShare"=>$rate*100000000,"secretPhrase"=>$secret,"feeNQT"=>$fee]);
      return $res;
    }elseif ($type =='asksell') {
      $fee = $this->ardor->post("placeAskOrder",["chain"=>2,"asset"=>$this->frasid,"quantityQNT"=>$qty*100000000,"priceNQTPerShare"=>$rate*100000000,"secretPhrase"=>$secret]);
      if (isset($fee->transactionJSON->feeNQT)) {
        $fee = $fee->transactionJSON->feeNQT;
      }else {
        return false;
      }
      $res = $this->ardor->post("placeAskOrder",["chain"=>2,"asset"=>$this->frasid,"quantityQNT"=>$qty*100000000,"priceNQTPerShare"=>$rate*100000000,"secretPhrase"=>$secret,"feeNQT"=>$fee]);
      return $res;
    }
  }

}
