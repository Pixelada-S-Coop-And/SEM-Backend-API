<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pruebas extends CI_Controller {
    public $ct = 1080;
   
    public function __construct(){
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->lang->load('index');
    }
    
    /*
        Administrator frontpage
    */
    public function index()
	{   
        $data = array();
        $this->view('pruebas',$data);

    }


    /*
        This function calls a view
    */
    private function view($view,$data)
    {
        if(empty($data['current'])) $data['current'] = 'home';
        if(empty($data['current_2'])) $data['current_2'] = '';
       $data['content'] = $this->load->view('panel/'.$view.'.php',$data, true);
        $this->load->view('panel/theme/page.php',$data);
    }

}