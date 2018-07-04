<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
/**
 	 * API Restfull
 	 * @author Indra Gunanda
	 */

class Api extends REST_Controller
{
    /**
 	 * Konstruktor
 	 * Konstruktor Berisi, pemuatan model "crud/main" dan "admin/car"  serta limitasi pengguna hanya untuk hak akses "admin"
 	 * @return json
	 */

    public function __construct()
    {
        parent::__construct();
        $this->load->model("crud/main");
    }
    /**
 	 * Initial Method
 	 *
 	 * @return json
	 */

    public function index_post()
    {
        $this->response([], 404);
    }
    /**
   * Initial Method
   *
   * @return json
   */
    public function index_get()
    {
        $this->response([], 404);
    }
    /**
   * Initial Method
   *
   * @return json
   */
    public function index_put()
    {
        $this->response([], 404);
    }
    /**
   * Initial Method
   *
   * @return json
   */
    public function index_delete()
    {
        $this->response([], 404);
    }
    /**
 	 * Get Car
 	 * Memuat data Tracking Mobil
 	 * @return json
	 */
   public function run_get()
   {
     $this->isadmin();
     $this->load->model("proses/bot");
     $this->main->setTable("botrules");
     $rules = $this->main->get();
     $data = [];
     $stat = true;
     foreach ($rules->result() as $key => $value) {
       $this->main->setTable("ardor");
       $getacc = $this->main->get();
       $limit = $getacc->num_rows();
       $random = rand(0,($limit-1));
       $getaccarray = $getacc->result();
       $ds = $this->bot->getpercentagecoin($value->spread,$getaccarray[$random]->publickey,$value->coin);
       $res = $ds;
       $data[] = $res;
       for ($i=0; $i <= 1 ; $i++) {
         if ($i == 0) {
           $tipe = "askbuy";
           $d = $this->bot->ardortrade($tipe,$res["buy"],$res["buyrate"],$getaccarray[$random]->secretkey);
         }else {
           $tipe = "asksell";
           $d = $this->bot->ardortrade($tipe,$res["sell"],$res["sellrate"],$getaccarray[$random]->secretkey);
         }
         if ($d != FALSE) {
           $data = [];
           $data["id_ardor"] = $getaccarray[$random]->id_ardor;
           $data["tipe"] = $tipe;
           $data["quantityQNT"] = $d->transactionJSON->attachment->quantityQNT;
           $data["priceNQTPerShare"] = $d->transactionJSON->attachment->priceNQTPerShare;
           $data["fullhash"] = $d->transactionJSON->fullHash;
           $this->main->setTable("ardor_botrecord");
           $ins = $this->main->insert($data);
           if (!$ins) {
             $this->main->setTable("log_failed");
             $this->main->insert(["publickey"=>$getaccarray[$random]->publickey,"reason"=>"Failed Insert"]);
             $stat = false;
             $msg = "Failed Insert";
             break;
           }
         }else {
           $this->main->setTable("log_failed");
           $this->main->insert(["publickey"=>$getaccarray[$random]->publickey,"reason"=>"Out Of Gas"]);
           $stat = false;
           $msg = "Out Of Gas";
           break;
         }
       }
     }
     if ($stat) {
       $this->response(["status"=>1,"msg"=>"success"]);
     }else {
       $this->response(["status"=>0,"msg"=>$msg]);
     }
   }
   public function lastrade_get()
   {
     $this->load->model("proses/bot");
     $d = $this->bot->getlastrade();
     $this->response($d);
   }
   public function getsaldo_get($getsaldo = "")
   {
     $this->load->model("proses/bot");
     $d = $this->bot->ceksaldo($getsaldo);
     $this->response($d);
   }

   public function isadmin()
   {
     if ($this->session->id_admin == NULL) {
       $this->response(["status"=>0,"msg"=>"Session Expired"]);
     }
   }
   public function login_post()
   {
     $dpost = $this->input->post(null,true);
     $this->main->setTable("admin");
     $dpost["password"] = md5($dpost["password"]);
     $get = $this->main->get($dpost);
     if ($get->num_rows() > 0) {
       $this->session->set_userdata((array) $get->row());
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function lissession_get()
   {
     $this->response($this->session);
   }
   public function adminupdate_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     foreach ($dpost as $key => $value) {
       if ($dpost[$key] == "") {
         unset($dpost[$key]);
       }
     }
     if (isset($dpost["password"])) {
       $dpost["password"] = md5($dpost["password"]);
     }
     $id = ["id_admin"=>$dpost['id_admin']];
     unset($dpost['id_admin']);
     $this->main->setTable("admin");
     $ins = $this->main->update($dpost,$id);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function admininsert_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     $dpost["password"] = md5($dpost["password"]);
     $this->main->setTable("admin");
     $ins = $this->main->insert($dpost);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function adminread_get($tipe='')
   {
     $this->isadmin();
     $this->main->setTable("admin");
     $get = $this->main->get();
     if ($tipe == "selectize") {
       $this->response($get->result());
     }else {
       $data = [];
       $data["data"] = [];
       foreach ($get->result() as $key => $value) {
         $data["data"][] = [$value->id_admin,$value->email,$value->username];
       }
       $this->response($data);
     }
   }
   public function admindelete_post()
   {
     $this->isadmin();
     $dpost = $this->main->post(null,true);
     $this->main->setTable("admin");
     $ins = $this->main->delete($dpost);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   // Tabel Ardor
   public function ardorread_get($tipe='')
   {
     $this->isadmin();
     $this->load->model("proses/bot");
     $this->main->setTable("ardor");
     $get = $this->main->get();
     if ($tipe == "selectize") {
       $this->response($get->result());
     }else {
       $data = [];
       $data["data"] = [];
       foreach ($get->result() as $key => $value) {
         $d = $this->bot->ceksaldo($value->publickey);
         $data["data"][] = [$value->id_ardor,$value->publickey,$d["fras"],$d["ignis"],$value->date_time];
       }
       $this->response($data);
     }
   }
   public function ardorinsert_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     $this->main->setTable("ardor");
     $ins = $this->main->insert($dpost);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function ardorupdate_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     foreach ($dpost as $key => $value) {
       if ($dpost[$key] == "") {
         unset($dpost[$key]);
       }
     }
     $id = ["id_ardor"=>$dpost['id_ardor']];
     unset($dpost['id_ardor']);
     $this->main->setTable("ardor");
     $ins = $this->main->update($dpost,$id);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function ardordelete_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     $this->main->setTable("ardor");
     $ins = $this->main->delete($dpost);
       $this->response(["status"=>1]);
       if ($ins) {
     }else {
       $this->response(["status"=>0]);
     }
   }
   // Ardor Bot Record
   public function ardorrecord_get($tipe='')
   {
     $this->isadmin();
     $this->main->setTable("ardor_botrecord");
     $get = $this->main->get();
     if ($tipe == "selectize") {
       $this->response($get->result());
     }else {
       $data = [];
       $data["data"] = [];
       foreach ($get->result() as $key => $value) {
         $this->main->setTable("ardor");
         $d = $this->main->get(["id_ardor"=>$value->id_ardor]);
         $value->publickey = $d->row()->publickey;
         $data["data"][] = [$value->id_ardor_botrecord,$value->tipe,$value->publickey,$value->status_callback,($value->quantityQNT/100000000),($value->priceNQTPerShare/100000000),$value->fullhash,$value->created];
       }
       $this->response($data);
     }
   }
   // Bot Rules
   public function botrulesinsert_post()
   {
     $this->isadmin();
     $dpost = $this->input->post(null,true);
     $this->main->setTable("botrules");
     $ins = $this->main->insert($dpost);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function botrulesread_get($tipe='')
   {
     $this->isadmin();
     $this->main->setTable("botrules");
     $get = $this->main->get();
     if ($tipe == "selectize") {
       $this->response($get->result());
     }else {
       $data = [];
       $data["data"] = [];
       foreach ($get->result() as $key => $value) {
         $data["data"][] = [$value->id_botrules,$value->spread,$value->coin,$value->created];
       }
       $this->response($data);
     }
   }
   public function botrulesupdate_post()
   {
     $dpost = $this->input->post(null,true);
     $this->main->setTable("botrules");
     $id = ["id_botrules"=>$dpost["id_botrules"]];
     unset($dpost["id_botrules"]);
     $ins = $this->main->update($dpost,$id);
     if ($ins) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   public function botrulesdelete_post()
   {
     $dpost = $this->input->post(null,true);
     $this->main->setTable("botrules");
     $del = $this->main->delete($dpost);
     if ($del) {
       $this->response(["status"=>1]);
     }else {
       $this->response(["status"=>0]);
     }
   }
   // Log Failed
   public function logfailedread_get($tipe='')
   {
     $this->isadmin();
     $this->main->setTable("log_failed");
     $get = $this->main->get();
     if ($tipe == "selectize") {
       $this->response($get->result());
     }else {
       $data = [];
       $data["data"] = [];
       foreach ($get->result() as $key => $value) {
         $data["data"][] = [$value->id_log_failed,$value->publickey,$value->reason,$value->created];
       }
       $this->response($data);
     }
   }
}
