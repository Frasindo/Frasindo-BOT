<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Indra Gunanda
 */
class Ardoraccount extends CI_Controller{
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
      base_url("assets/main/ardoraccount.js")
    ],true);

    $build = [
      "block_title"=>"Ardor Account"
    ];
    // Render
    $this->template->renderHTML(['head','ardoraccount','foot'],['title'=>"Ardor Account",'other'=>$build]);
  }

}
