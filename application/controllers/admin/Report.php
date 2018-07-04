<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Indra Gunanda
 */
class Report extends CI_Controller{
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
      base_url("assets/main/report.js")
    ],true);

    $build = [
      "block_title"=>"Report Bot Activity"
    ];
    // Render
    $this->template->renderHTML(['head','report','foot'],['title'=>"Report Bot Activity",'other'=>$build]);
  }

}
