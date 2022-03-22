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

class Message extends CI_Controller {
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
        This API method requests the messages published from a certain moment in time
        Parameters: token, email, pass
    */

    public function list($data){  
        if(empty($data->session_id)||!$this->affiliate_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');

        $affiliate_id = $this->affiliate_model->affiliate_session($data->session_id);

        $affiliate = $this->affiliate_model->affiliate_by_id($affiliate_id);
        if(!empty($data->last_time))
            $last_time = intval($data->last_time);
        else
            $last_time = 0;
        if(!empty($last_time)){
            if($last_time>time())
                $this->response_error('Fecha de última consulta incorrecta');
            else   $last_time = date('Y-m-d H:i:s', $last_time);

        } 
        
        $data->time = time();
        $data->messages = $this->message_model->messages($affiliate['section_id'], $last_time);

        $this->response_ok('Últimas notificaciones', $data);
    }

     /*
        This API method requests a messages published by its ID
        Parameters: token, email, pass
    */

    public function item($data){  
        if(empty($data->session_id)||!$this->affiliate_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        if(empty($data->message_id))
            $this->response_error('Debes introducir el ID del mensaje');

        $data->message = $this->message_model->message(intval($data->message_id));

        $this->response_ok('Información de notificación', $data);
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

        if(empty($data->token)||$data->token!=$this->token){
            $this->response_error('Token incorrecto');
        }

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