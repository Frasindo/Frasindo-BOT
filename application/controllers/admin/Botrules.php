<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Indra Gunanda
 */
class Botrules extends CI_Controller{
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
      base_url("assets/main/botrules.js")
    ],true);

    $build = [
      "block_title"=>"Bot Rules"
    ];
    // Render
    $this->template->renderHTML(['head','botrules','foot'],['title'=>"Bot Rules",'other'=>$build]);
  }

}
