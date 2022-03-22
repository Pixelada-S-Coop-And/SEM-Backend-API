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

class User extends CI_Controller {
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
        This API method opens a user session by passing it the correct email and password as parameters
        Parameters: token, email, pass
    */
    private function login($data){ 
        if(empty($data->email)||empty($data->pass))
            $this->response_error('Debes introducir email y contraseña');

        //Buscamos el usuario en la Base de datos
        $user = (object) $this->user_model->user($data->email);
        
        //Si no se encuentra el usuario
        if(empty($user->email))
            $this->response_error('No existe el usuario introducido');
        
        //Si la contraseña no es correcta
        if(md5($data->pass)!=$user->pass)
            $this->response_error('La contraseña no es correcta');

        $data = $this->user_model->login_user($user->email);
        
        $data['server_time'] = time();

        $this->response_ok('Login correcto', $data);
    }

    /*
        This API method closes a user session by passing a session id as parameters
        Parameters: token, session_id
    */
    public function logout($data){  
        if(!empty($data->session_id))
            $this->user_model->logout($data->session_id);

        $this->response_ok('Sesión cerrada');
    }
    
    
    /*
        This API method creates a user account
        Parameters: token, email, pass, name
    */
    private function register($data){
        if(empty($data->name)||empty($data->pass)||empty($data->email))
            $this->response_error('Debes introducir los campos obligatorios');

        if(!$this->user_model->email_validation($data->email)){
            $this->response_error('El email introducido no es válido');
            return false;
        }

        if($this->user_model->user_exists($data->email)){
            $this->response_error('El email introducido ya fue registrado');
            return false;
        }
  
        if(strlen($data->pass)<6){
            $this->response_error('La contraseña debe tener al menos 6 caracteres');
            return false;
        }

        $this->user_model->new_user($data);

        $user = (object) $this->user_model->user($data->email);
        $token = base64_encode(json_encode(array('time' => time(), 'email' => $user->email, 'pass' => $user->pass)));
        $link = site_url('cuenta/confirmar-email/?token='.$token);
        $message = '<p>Hola '.$user->name.',</p>';
        $message .= '<p>Te has registrado en SEM. Para confirmar tu cuenta de correo electrónico y activar tu cuenta haz click en el siguiente enlace:</p>';
        $message .= '<p><a href="'.$link.'" style="display: block; text-align: center; width: 200px; font-weight: bold; padding: 10px 20px; text-decoration: none; border-radius: 10px; background: #222222; color: #fff;" target="_blank">Confirmar</a></p>';
        $this->send_email($user->email, 'SEM - Activar cuenta de usuario',$message);

        $this->response_ok('Usuario registrado correctamente', $data);
    }

    /*
        This API method edits the data of a user with the session open
        Parameters: token, session_id, email, pass, name
    */
    public function edit($data){  
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');

        $user_id = $this->user_model->user_session($data->session_id);

        if(empty($data->name)||empty($data->email))
            $this->response_error('Debes introducir los campos obligatorios');

        if(!$this->user_model->email_validation($data->email))
            $this->response_error('El email introducido no es válido');
        
        if($this->user_model->user_exists_edit($user_id, $data->email))
            $this->response_error('El email introducido ya fue registrado por otro usuario');
  
        if(!empty($data->pass)&&strlen($data->pass)<6)
            $this->response_error('La contraseña debe tener al menos 6 caracteres');

        $this->user_model->edit_user($user_id, $data);

        $this->response_ok('Usuario editado correctamente', $data);
    }

    /*
        This API method returns the data of a user with the session open
        Parameters: token, session_id
    */
    public function data($data){  
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');

        $user_id = $this->user_model->user_session($data->session_id);
        $user = $this->user_model->user_by_id($user_id);
         
        $this->response_ok('Datos de usuario', $user);
    }

    public function deleteaccount($data){  
        if(empty($data->session_id)||!$this->user_model->session_exists($data->session_id))
            $this->response_error('Usuario no logueado');

        $user_id = $this->user_model->user_session($data->session_id);
        $this->user_model->delete_user($id);
         
        $this->response_ok('Cuenta de usuario eliminada', true);
    }


    


    /*
        This API method resets a user's password by passing an email as a parameter
        Parameters: token, session_id, email
    */    
    private function resetpass($data){
        if(empty($data->email))
            $this->response_error('Debes introducir tu email');

        //Buscamos el usuario en la Base de datos
        $user = (object) $this->user_model->user($data->email);
        
        //Si no se encuentra el usuario
        if(empty($user->email))
            $this->response_error('No existe el usuario introducido');
        
        $token = base64_encode(json_encode(array('time' => time(), 'email' => $user->email, 'pass' => $user->pass)));
        $link = site_url('cuenta/recuperar-contrasena/?token='.$token);
        $message = '<p>Hola '.$user->name.',</p>';
        $message .= '<p>Para recuperar tu contraseña haz click en el siguente enlace:</p>';
        $message .= '<p><a href="'.$link.'" style="display: block; text-align: center; width: 200px; font-weight: bold; padding: 5px 20px; text-decoration: none; border-radius: 10px; background: #222222; color: #fff;" target="_blank">Recuperar contraseña</a></p>';
        $this->send_email($user->email, 'Autismo Códoba - Recuperar contraseña', $message);
        $data = array('email' => $data->email);
        $this->response_ok('Te hemos enviado un email. Revisa tu bandeja de entrada para recuperar tu contraseña.', $data);
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

    private function send_email($to, $subject, $message, $title='', $subtitle=''){
        $this->load->library('email');
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->option_model->option('smtp_host');
        $config['smtp_user'] = $this->option_model->option('smtp_user');
        $config['smtp_pass'] = $this->option_model->option('smtp_pass');
        $config['smtp_port'] = intval($this->option_model->option('smtp_port'));
        $config['smtp_crypto'] = $this->option_model->option('smtp_crypto');
        $config['charset'] = $this->option_model->option('smtp_charset');
        $config['mailtype'] = 'html';
        $config['starttls']  = true;
        $this->email->initialize($config);
        $this->email->set_mailtype("html"); 
        $this->email->from($this->option_model->option('smtp_from'), 'SEM');
        $this->email->to($to);
 
        $this->email->subject($subject);

        if(empty($title))   $title = $subject;
        $message = $this->email_content($title, $subtitle, $message);

        $this->email->message($message);
        
        $result = $this->email->send();

      //  echo $this->email->print_debugger();

        return $result;
    }

    private function email_content($title, $subtitle, $content){
        $template_path = FCPATH.'application/views/mail/form-mail-template.html';
        $template = file_get_contents($template_path) ;
        $template = str_replace('{{logo}}', site_url('assets/images/logo.png'), $template);
        $template = str_replace('{{subject}}', $title);
        $template = str_replace('{{title}}', $title);
        $template = str_replace('{{subtitle}}', $subtitle);
        $template = str_replace('{{content}}', $content);
        return $template;
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