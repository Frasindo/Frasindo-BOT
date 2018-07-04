<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Indra Gunanda
 */
class Home extends CI_Controller{
  /**
 	 * Konstruktor
 	 *
 	 * @return void
	 */

  public function __construct()
  {
    parent::__construct();
    $this->load->model("crud/main");
    if ($this->session->id_admin == NULL) {
      redirect("login");
    }
  }
  /**
 	 * Index Home
 	 *
 	 * @return void
	 */

  function index()
  {
    $this->template->setFolder("admin");
    $this->template->defaultStyle("admin");
    $this->template->setjs([
      base_url("assets/main/home.js")
    ],true);
    $this->main->setTable("ardor");
    $a = $this->main->get();
    $this->main->setTable("log_failed");
    $as = $this->main->get();
    $build = [
      "block_title"=>"Dashboard",
      "total_account"=>$a->num_rows(),
      "total_log"=>$as->num_rows()
    ];
    // Render
    $this->template->renderHTML(['head','home','foot'],['title'=>"Dashboard",'other'=>$build]);
  }

}
