<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Model{
  public $frasid;
  public $crowdsale_price = 2.48;
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
  public function getlastrade()
  {
      $last = $this->ardor->post("getLastTrades",["chain"=>2,"assets"=>$this->frasid]);
      if (isset($last->trades)) {
        return $last;
      }else {
        return false;
      }
  }
  public function ceksaldo($acc='')
  {
    $last = $this->ardor->post("getBalances",["chain"=>2,"account"=>$acc]);
    $alldata = [];
    if (isset($last->balances)) {
      $saldoNative = 0;
      foreach($last->balances as $s => $a){
        if(isset($a->balanceNQT)){
          $saldoNative = $a->balanceNQT;
          break;
        }
      }
      $saldoNative = $saldoNative / 100000000;
    }else {
      return false;
    }
    $last = $this->ardor->post("getAccountAssets",["asset"=>$this->frasid,"account"=>$acc]);
    $saldo = 0;
    if (isset($last->quantityQNT)) {
      $saldo = $last->quantityQNT;
      $saldo = $saldo / 100000000;
    }else {
      return false;
    }
    return ["fras"=>$saldo,"ignis"=>$saldoNative];
  }
  public function getpercentagecoin($spread="",$acc='',$percent='')
  {
      $last = $this->ardor->post("getBalances",["chain"=>2,"account"=>$acc]);
      $alldata = [];
      if (isset($last->balances)) {
        $saldoNative = 0;
        foreach($last->balances as $s => $a){
          if(isset($a->balanceNQT)){
            $saldoNative = $a->balanceNQT;
            break;
          }
        }
        $saldoNative = $saldoNative / 100000000;
        $saldoNative = $saldoNative*($percent/100);
      }else {
        return false;
      }
      $last = $this->ardor->post("getAccountAssets",["asset"=>$this->frasid,"account"=>$acc]);
      $saldo = 0;
      if (isset($last->quantityQNT)) {
        $saldo = $last->quantityQNT;
        $saldo = $saldo / 100000000;
        $saldo = $saldo*($percent/100);
      }else {
        return false;
      }
      $lget = $this->getlastrade();
      if ($lget != FALSE) {
        $this->crowdsale_price = ($lget->trades[0]->priceNQTPerShare/100000000);
        return ["sell"=>substr($saldo,0,5),"sellrate"=>substr($this->crowdsale_price+($this->crowdsale_price*(($spread/2)/100)),0,5),"buy"=>substr($saldoNative,0,5),"buyrate"=>substr($this->crowdsale_price - ($this->crowdsale_price*(($spread/2)/100)),0,5)];
      }else {
        return false;
      }
  }

}
