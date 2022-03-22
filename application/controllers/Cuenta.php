<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuenta extends CI_Controller {
    public $ct = 1080;
   
    public function __construct(){
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->lang->load('index');
    }
    
    public function index()
	{   
        redirect(site_url('admin'));
        //$this->view('dashboard', array());

    }

    public function recuperar_contrasena()
	{   
        $_GET = $this->input->get(); 
        $data = array();
        if(!empty($_GET['token'])){
            $token =  $_GET['token'];
            $data = (array) json_decode(base64_decode($token)); 
            if(!empty($data['email'])&&!empty($data['pass'])&&!empty($data['time'])){ 
                $user = $this->user_model->user($data['email']);
                if(!empty($user['id'])&&$user['pass']==$data['pass']&&!empty($data['time'])){ 
                    if((time()-intval($data['time']))>86400){ 
                        $data['error'] = 'El enlace para recuperar la contraseña ha caducado.';
                    }else{
                        $this->recuperar_contrasena2($user, $token);
                        return true;
                    }
                }else{
                    redirect(site_url('cuenta/recuperar-contrasena')); return true;
                }
            }else redirect(site_url('cuenta/recuperar-contrasena')); return true;
        }

        $_POST = $this->input->post(); 
        if(!empty($_POST)){ 
            $user = (object) $this->user_model->user($_POST['email']);
            
            if(empty($user->email)){
                $data['error'] = 'No existe ningún user con el email introducido';
            }else{
                $data['error'] = '';
                $token = base64_encode(json_encode(array('time' => time(), 'email' => $user->email, 'pass' => $user->pass)));
                $link = site_url('cuenta/recuperar-contrasena/?token='.$token);
                $message = '<p>Hola '.$user->name.',</p>';
                $message .= '<p>Para recuperar tu contraseña haz click en el siguente enlace:</p>';
                $message .= '<p><a href="'.$link.'" style="display: block; text-align: center; width: 200px; font-weight: bold; padding: 5px 20px; text-decoration: none; border-radius: 10px; background: #222222; color: #fff;" target="_blank">Recuperar contraseña</a></p>';
                $this->send_email($user->email, 'Autismo Códoba - Recuperar contraseña', $message);
                $data['error'] = 'Te he enviado un email. Revisa tu bandeja de entrada.';
            }
        }

      //  $this->load->view('cuenta/recuperar.php', $data);
    }

    public function confirmar_email(){
        $_GET = $this->input->get(); 
        $data = array();
        if(!empty($_GET['token'])){
            $token =  $_GET['token'];
            $data = (array) json_decode(base64_decode($token)); 
            if(!empty($data['email'])&&!empty($data['pass'])&&!empty($data['time'])){ 
                $user = $this->user_model->user($data['email']);
                if(!empty($user['id'])&&$user['pass']==$data['pass']&&!empty($data['time'])){ 
                    if((time()-intval($data['time']))>86400){ 
                        $data['msj'] = 'Error. El enlace ha caducado.';
                    }else{
                        $this->confirmar_email2($user, $token);
                        return true;
                    }
                }
            }
        }

        $this->load->view('cuenta/confirmar.php', $data);
    }

    private function recuperar_contrasena2($user, $token){
        $data = array('token' => $token);
        $data['user'] = $user;
        if(!empty($_POST['pass'])){
            $this->user_model->change_pass($user['id'], $_POST['pass']);
            $data['error'] = 'Tu contraseña se ha cambiado correctamente';
        }

        $this->load->view('cuenta/recuperar2.php', $data);
    }

    private function confirmar_email2($user, $token){
        $data = array('token' => $token);
        $data['user'] = $user;
        $this->user_model->activate_user($user['id']);
        $data['msj'] = 'Tu cuenta se ha validado correctamente';
        
        $this->load->view('cuenta/confirmar.php', $data);
    }

    private function send_email($to, $subject, $message){
        // $to = $item['email'];
        $this->load->library('email');
        //Indicamos el protocolo a utilizar
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->option_model->option('smtp_host');
        $config['smtp_user'] = $this->option_model->option('smtp_user');
        $config['smtp_pass'] = $this->option_model->option('smtp_pass');
        $config['smtp_port'] = intval($this->option_model->option('smtp_port'));
        $config['smtp_crypto'] = $this->option_model->option('smtp_crypto');
        $config['charset'] = $this->option_model->option('smtp_charset');
        $config['mailtype'] = 'html';         
 
         $this->email->initialize($config);
 
         $this->email->from($this->option_model->option('smtp_from'), 'SEM');
 
         $this->email->to($to);
 
         $message = '<p style="margin-bottom:30px;"><img src="'.site_url('assets/images/logo.png').'" alt="SEM" width="300" /></p>'.$message;
         $this->email->subject($subject);
         $this->email->message($message);
 
         
         if($this->email->send()){ 
              return true;
         }else{  
           //  echo $this->email->print_debugger(); 
             return false;
         }
     }

     private function view($view, $data)
    {
        if(empty($data['current']))  $data['current'] = 'home';
        if(empty($data['current_2']))  $data['current_2'] = '';
        $data['content'] = $this->load->view('panel/'.$view.'.php', $data, true);
        $this->load->view('cuenta/page.php', $data);
    }
}