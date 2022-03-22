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

class Form extends CI_Controller {
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
        This API method send the form message to advisers
        Parameters: token, session_id, subject, content
    */
    public function send($data){  
        if(empty($data->session_id)||!$this->affiliate_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');
        if(empty($data->subject) || empty($data->content)){
            $this->response_error('Debes introducir asunto y contenido de mensaje');
        }
        $affiliate_id = $this->affiliate_model->affiliate_session($data->session_id);
        $affialiate = $this->affiliate_model->affiliate_by_id($affiliate_id);

        $data->affiliate = $affiliate;

        $message = $data->content;
        $message .= '<br /><hr /><br /><pre>'.print_r($affiliate, true).'</pre>';
        $this->send_email(array($this->option_model->option('form_email', 'asesoria@autonomiasur.org')), 'SEM App - '.$data->subject, $message);
        
        $this->response_ok('Mensaje enviado correctamente', $data);
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

    private function send_email($to, $subject, $message){
        $headers = "From: SEM <web@".$_SERVER['HTTP_HOST'].">\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	// no necesario (Joa)
	// $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message = '<p style="margin-bottom:30px; text-align: center;"><img style="background: #222222; padding: 20px 40px;" src="'.site_url('assets/images/logo.png').'" alt="SEM" width="300" /></p>'.$message;
        if(mail($to, $subject,$message, $headers)) return true;
        else return false;
    }


    public function login_post(){  
        $this->login($this->post_data());
    }

    public function login_get(){  
        $this->login($this->get_data());
    }

    public function logout_post(){  
        $this->logout($this->post_data());
    }

    public function logout_get(){  
        $this->logout($this->get_data());
    }

    public function register_post(){  
        $this->register($this->post_data());
    }

    public function register_get(){  
        $this->register($this->get_data());
    }

    public function resetpass_post(){  
        $this->resetpass($this->post_data());
    }

    public function resetpass_get(){  
        $this->resetpass($this->get_data());
    }

    public function edit_post(){  
        $this->edit($this->post_data());
    }

    public function edit_get(){  
        $this->edit($this->get_data());
    }

    public function data_post(){  
        $this->data($this->post_data());
    }

    public function data_get(){  
        $this->data($this->get_data());
    }

    public function deleteaccount_post(){  
        $this->deleteaccount($this->post_data());
    }

    public function deleteaccount_get(){  
        $this->deleteaccount($this->get_data());
    }

}
