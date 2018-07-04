<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Indra Gunanda
 */
class Login extends CI_Controller{
  /**
 	 * Konstruktor
 	 *
 	 * @return void
	 */

  public function __construct()
  {
    parent::__construct();
    $this->load->model("crud/main");
    if ($this->session->id_user > 0) {
      redirect("admin");
    }
  }
  /**
 	 * Index Home
 	 *
 	 * @return void
	 */

  function index()
  {
    $this->template->setFolder("login");
    $this->template->defaultStyle("login");
    $this->template->setjs([
      base_url("assets/main/login.js")
    ],true);
    // Render
    $build = [];
    $this->template->renderHTML(['head','home','foot'],['title'=>"Halaman Masuk",'other'=>$build]);
  }

}
