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

class Event extends CI_Controller {
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
        This API method returns a list of all user's events in the database 
        Parameters: token, session_id
    */
    private function list($data){
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $user_id = $this->user_model->user_session($data->session_id);

        $events = $this->event_model->events_user($user_id);

        $this->response_ok('Listado de citas', $events);
    }


    /*
        This API method returns the data of a specific event
        Parameters: token, session_id, event_id
    */
    private function item($data){
        if(empty($data->event_id)||empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $user_id = $this->user_model->user_session($data->session_id);

        $event = $this->event_model->event(intval($data->event_id));

        $this->response_ok('Contenido de cita', $event);
    }


    /*
        This API method adds an event in the database
        Parameters: token, session_id, datetime, description, sequences
    */
    private function add($data){
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $data->user_id = $this->user_model->user_session($data->session_id);

        $event = $this->event_model->new_event($data);

        if(!empty($event)) $event = array('event_id' => intval($event));
        else $event = false;
        
        if(!$event) $this->response_error('Crear nuevo evento - Valores incorrectos');
        else    $this->response_ok('Crear nuevo evento', $event);
    }

    /*
        This API method edits a specific event
        Parameters: token, session_id, event_id, datetime, description, sequences
    */
    private function edit($data){
        if(empty($data->event_id)||empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');

        $data->user_id = $this->user_model->user_session($data->session_id);
        
        $event = $this->event_model->edit_event($data->event_id, $data);

        if(empty($event))  $this->response_error('Error editando cita');

        $this->response_ok('Editar evento', $event);
    }

    /*
        This API method deletes a specific event
        Parameters: token, session_id, event_id
    */
    private function delete($data){
        if(empty($data->event_id)||empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        
        $data->user_id = $this->user_model->user_session($data->session_id);

        $event = $this->event_model->delete_event_user($data->event_id, $data->user_id);

        $this->response_ok('Eliminar evento', $event);
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

    public function add_post(){  
        $this->add($this->post_data());
    }

    public function add_get(){  
        $this->add($this->get_data());
    }

    public function edit_post(){  
        $this->edit($this->post_data());
    }

    public function edit_get(){  
        $this->edit($this->get_data());
    }

    public function delete_post(){  
        $this->delete($this->post_data());
    }

    public function delete_get(){  
        $this->delete($this->get_data());
    }
}