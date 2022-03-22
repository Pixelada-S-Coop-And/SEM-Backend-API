<?php
/* 
 *  Copyright 2020 Pixelada s. Coop. And. <info (at) pixelada (dot) org>
 *  
 *  This file is part of SEM
 *  
 *  Detnaltea is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *  
 *  SEM is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  You should have received a copy of the GNU General Public License
 *  along with SEM.  If not, see <https://www.gnu.org/licenses/>.
 */

/*
 *  Copyright 2020 Pixelada s. Coop. And. <info (at) pixelada (dot) org>
 *
 *  This file is part of AppAutismoCórdoba
 *
 *  AppAutismoCórdoba is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  AppAutismoCórdoba is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with AppAutismoCórdoba.  If not, see <https://www.gnu.org/licenses/>.
 */


use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Pictogram extends CI_Controller {
    use REST_Controller { REST_Controller::__construct as private __resTraitConstruct; }
    public $token = ''; 

    function __construct(){
        parent::__construct();
        $this->__resTraitConstruct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->lang->load('index');
        $this->token = $this->option_model->get_token();
    }

    /*
        This API method returns a list of all pictograms in the database 
        Parameters: token, session_id
    */
    private function list($data){
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $pictograms = $this->pictogram_model->pictograms();

        $this->response_ok('Listado de pictogramas', $pictograms);
    }

    /*
        This API method returns the data of a specific pictogram
        Parameters: token, session_id, pictogram_id
    */
    private function item($data){
        if(empty($data->pictogram_id)||empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $pictogram = $this->pictogram_model->pictogram(intval($data->pictogram_id));

        $this->response_ok('Contenido de pictograma', $pictogram);
    }

    /*
        Error message
    */
    private function response_error($msg='', $data=null){
        $response = array();
        $response['result'] = 'error';
        $response['msg'] = $msg;
        $response['data'] = $data;
        $this->response($response, 200);
        exit();
    }


    /*
        Ok message
    */
    private function response_ok($msg='', $data=null){
        $response = array();
        $response['result'] = 'ok';
        $response['msg'] = $msg;
        $response['data'] = $data;
        $this->response($response, 200);
        exit();
    }


    private function post_data(){
        header("Access-Control-Allow-Origin: *");

        $data = (object) $this->input->post();
        if(empty($data->token))
            $data = (object) json_decode(file_get_contents("php://input"));

        if(empty($data->token)||$data->token!=$this->token)
            $this->response_error('Token incorrecto');

        return $data;
    }


    //GET

    private function get_data(){
        header("Access-Control-Allow-Origin: *"); 

        $data = (object) $this->input->get();

        if(empty($data->token)||$data->token!=$this->token)
            $this->response_error('Token incorrecto');

        return $data;
    }

    public function list_post(){  
        $this->list($this->post_data());
    }

    public function list_get(){  
        $this->list($this->get_data());
    }

    public function item_post(){  
        $this->item($this->post_data());
    }

    public function item_get(){  
        $this->item($this->get_data());
    }
}