<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 	 * Template Engine V.0.1
 	 * @author Indra GUnanda
	 */

class Template {
  public $ci;
  public $css = [];
  public $js = [];
  public $menu = [];
  public $folder = "";
  /**
 	 * Get Instance CI, Load Helper URL & Parser
 	 *
 	 * @return void
	 */

  public function __construct()
  {
    $this->ci =& get_instance();
    $this->ci->load->helper('url');
    $this->ci->load->library('parser');
  }
  /**
 	 * Set View Folder Dalam Contoh Kasus Berikut
 	 * -- View
   * ---- admin
   * Itu berarti di daalm setFolder ada pilih
   * $this->template->setFolder("admin");
 	 * @param string $data
 	 * @return void
	 */

  public function setFolder($data='')
  {
    $this->folder = $data;
  }
  /**
 	 * Default Style adalah CSS & JS default pada saat intitialisasi projek awal
 	 *
 	 * @param string $type
 	 * @return void
	 */

  public function defaultStyle($type='')
  {
    if($type == "admin"){
      $css = [
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        '//cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css',
        base_url("assets/admin/css/style.css"),
        "//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css",
        '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.bootstrap3.min.css',
        '//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css'
      ];
      $js = [
        base_url("assets/admin/node_modules/jquery/jquery-3.2.1.min.js"),
        base_url("assets/admin/node_modules/popper/popper.min.js"),
        base_url("assets/admin/node_modules/bootstrap/dist/js/bootstrap.min.js"),
        base_url("assets/admin/js/perfect-scrollbar.jquery.min.js"),
        base_url("assets/admin/js/waves.js"),
        base_url("assets/admin/js/sidebarmenu.js"),
        base_url("assets/admin/node_modules/sticky-kit-master/dist/sticky-kit.min.js"),
        base_url("assets/admin/node_modules/sparkline/jquery.sparkline.min.js"),
        base_url("assets/admin/js/custom.min.js"),
        '//cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js',
        '//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js',
        '//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.js',
        base_url("assets/admin/js/jq.redir.js")
      ];
      $this->css = $css;
      $this->js = $js;
    }elseif ($type == "login") {
      $css = [
        base_url("assets/login/vendor/bootstrap/css/bootstrap.min.css"),
        base_url("assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css"),
        base_url("assets/login/vendor/animate/animate.css"),
        base_url("assets/login/vendor/css-hamburgers/hamburgers.min.css"),
        base_url("assets/login/vendor/select2/select2.min.css"),
        base_url("assets/login/css/util.css"),
        base_url("assets/login/css/main.css")
      ];
      $js = [
        base_url("assets/login/vendor/jquery/jquery-3.2.1.min.js"),
        base_url("assets/login/vendor/bootstrap/js/popper.js"),
        base_url("assets/login/vendor/bootstrap/js/bootstrap.min.js"),
        base_url("assets/login/vendor/select2/select2.min.js"),
        base_url("assets/login/vendor/tilt/tilt.jquery.min.js"),
        '//cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js',
        base_url("assets/login/js/main.js")
      ];
      $this->css = $css;
      $this->js = $js;
    }else{
      exit("Default Style Wrong");
      die();
    }
  }
  /**
 	 * Set CSS untuk menambah kan CSS baru atau mengganti default CSS yang sudah di load sebelumnya dengan option setcss(DATA_ARRAY_URL,REPLACE OR APPEND)
 	 * @example $this->template->setcss(["URL_CSS"],TRUE)
 	 * @param mixed $data,$append
 	 * @return void
	 */

  public function setcss($data=[],$append=false)
  {
    if($append){
      foreach ($data as $key => $value) {
        array_push($this->css,$value);
      }
    }else{
      $this->css = $data;
    }
  }
  /**
 	 * Set JS untuk menambah kan JS baru atau mengganti default JS yang sudah di load sebelumnya dengan option setcss(DATA_ARRAY_URL,REPLACE OR APPEND)
 	 * @example $this->template->setcss(["JS"],TRUE)
 	 * @param mixed $data,$append
 	 * @return void
	 */
  public function setjs($data=[],$append=false)
  {
    if($append){
      foreach ($data as $key => $value) {
        array_push($this->js,$value);
      }
    }else{
      $this->js = $data;
    }
  }
  /**
 	 * Under Contrsuction
 	 *
	 */

  public function menuBuilder($datamenu = [],$append = false)
  {
    if($append){
      foreach ($datamenu as $key => $value) {
        array_push($this->menu,$value);
      }
    }else{
      $this->menu = $datamenu;
    }
  }
  /**
 	 * renderHTML untuk rendering semua data yang sudah di susun ke dalam bentuk HTML dengan memakai bantuan library parser Codeigniter 3
   * Input Render yang pertama adalah data array 3 View , diaman yang biasa kita kenal dengan header,body,footer di dalam view dan terletak di folder pages,untuk urutan filder header dan footer di letakan di folder theme dan body di pages
 	 * @example $this->template->renderHTML(["heder","body","footer"],["title"=>"Test Page"]);
 	 * @param array $data,$page_data
 	 * @return void
	 */

  public function renderHTML($data=[],$page_data=[])
  {

    $css = $this->css;
    $js = $this->js;
    $cssready = [];
    $jsready = [];
    $i = 0;
    foreach ($css as $key => $value) {
      $cssready[$i++]["url"] = $value;
    }
    $i = 0;
    foreach ($js as $key => $value) {
      $jsready[$i++]["url"] = $value;
    }
    if(count($page_data) > 0){
      $data_asset = [];
      if(isset($page_data["other"])){
        foreach ($page_data["other"] as $key => $value) {
          $data_asset[$key] = $value;
        }
      }
      $data_asset["title"] = $page_data["title"];
      $data_asset["css"] = $cssready;
      $data_asset["js"] = $jsready;
    }
    if(isset($data[0])){
      $this->ci->parser->parse($this->folder."/theme/".$data[0], $data_asset);
    }
    if(isset($data[1])){
      $this->ci->parser->parse($this->folder."/pages/".$data[1], $data_asset);
    }
    if(isset($data[2])){
      $this->ci->parser->parse($this->folder."/theme/".$data[2], $data_asset);
    }
  }
}
