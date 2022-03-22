<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
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
        $this->check_login();
        redirect(site_url('admin/affiliates'));
        //$this->view('dashboard', array());

    }

    /*
        Login page
    */
    public function login()
	{   
        $this->load->library('session');
        
        if($this->user_model->is_logged_in()){
            redirect(site_url('admin')); return true;
        }

       $data = array();
        $_POST = $this->input->post(); 
        if(!empty($_POST)){ 
            $user = (object) $this->user_model->user($_POST['email']);
            
            if(empty($user->email)||md5($_POST['pass'])!=$user->pass){
               $data['error'] = 'Email o contraseña incorrectos';
            }else{ 
             $data = $this->user_model->login_user($user->email);
              if($this->user_model->is_logged_in()){
                    redirect(site_url('admin')); return true;
               }
            }
        }

        $this->load->view('panel/theme/login.php',$data);
    }


    /*
        Recover password page
    */
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
                    redirect(site_url('admin/recuperar-contrasena')); return true;
                }
            }else redirect(site_url('admin/recuperar-contrasena')); return true;
        }

        $_POST = $this->input->post(); 
        if(!empty($_POST)){ 
            $user = (object) $this->user_model->user($_POST['email']);
            
            if(empty($user->email)){
               $data['error'] = 'No existe ningún usuario administrador con el email introducido';
            }else{
               $data['error'] = '';
              
                $token = base64_encode(json_encode(array('time' => time(), 'email' => $user->email, 'pass' => $user->pass)));
                $link = site_url('admin/recuperar-contrasena/?token='.$token);
                $message = '<p>Hola '.$user->name.',</p>';
                $message .= '<p>Para recuperar tu contraseña haz click en el siguente enlace:</p>';
                $message .= '<p><a href="'.$link.'" style="display: block; text-align: center; width: 200px; font-weight: bold; padding: 5px 20px; text-decoration: none; border-radius: 10px; background: #222222; color: #fff;" target="_blank">Recuperar contraseña</a></p>';
                $this->send_email($user->email, 'SEM - Recuperar contraseña', $message);
               $data['error'] = 'Te he enviado un email. Revisa tu bandeja de entrada.';
            }
        }

        $this->load->view('panel/theme/recuperar.php',$data);
    }

    /*
        Recover password step 2 page
    */
    private function recuperar_contrasena2($user, $token){
       $data = array('token' => $token);
       $data['user'] = $user;
        if(!empty($_POST['pass'])){
            $this->user_model->change_pass($user['id'], $_POST['pass']); 
           $data['error'] = 'Tu contraseña se ha cambiado correctamente <script> setTimeout(function(){ window.location.href ="'.site_url('admin/login').'" }, 5000); </script>';
           
        }

        $this->load->view('panel/theme/recuperar2.php',$data);
    }


    /*
        This function sends and email
    */

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
        $this->email->initialize($config);

        $this->email->from($this->option_model->option('smtp_from'), 'SEM');
        $this->email->to($to);
        
        $this->email->subject($subject);
        



        if(empty($title))   $title = $subject;
        $message = $this->email_content($title, $subtitle, $message);

        //if(mail($to, $subject, $message, $headers)) return true;
        //else return false;

        $this->email->message($message);
        
        $result = $this->email->send();

      //  echo $this->email->print_debugger();

        return $result;
    }

    private function email_content($title, $subtitle, $content){
        $template_path = FCPATH.'application/views/mail/form-mail-template.html';
        $template = file_get_contents($template_path) ;
        $template = str_replace('{{logo}}', site_url('assets/images/logo.png'), $template);
        $template = str_replace('{{subject}}', $title, $template);
        $template = str_replace('{{title}}', $title, $template);
        $template = str_replace('{{subtitle}}', $subtitle, $template);
        $template = str_replace('{{content}}', $content, $template);
        return $template;
    }


    /*
        Logout and redirect function
    */
    public function logout(){
        if($this->user_model->is_logged_in())
            $this->user_model->logout($this->user_model->session_id());

        $this->check_login();
    }
   
    /*
        Settings page
    */
    public function settings()
	{
        $this->check_login();
        $this->check_admin();

        if(!empty($_POST)){
            $this->option_model->save_options($_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/settings'));
            return true;
        }

        $data = array('current' => 'settings', 'title'=> 'Configuración', 'options' => $this->option_model->options(), 'option_model' => $this->option_model);
        
        $this->view('settings/settings',$data);
    }

    /*
        Users section controller
    */
    public function users($action=null, $id=null)
	{   
        $this->check_login();
        $data = array();
        
        if(empty($action)){  
            $get = $this->input->get();
            $back = site_url('admin/users');

            $param = '';
            foreach($get as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
            if(!empty($param))  $back .= '?'.$param;
            $this->session->set_userdata(array('users_back_link' => $back)); 
            $this->users_list(); return true;  
        }

        $user = $this->user_model->user_by_id($id);
       
        if(empty($user)&&($action=='edit'||$action=='delete')){
            redirect(site_url('admin/users'));
           
            return true;
        }
        $current_user = $this->user_model->current_user();
        $role = $current_user->role;
        
        switch($action){
            case 'new': 
                $this->new_user(); break;
            case 'edit': 
                if($role!=0&&$current_user->id!=$id){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->edit_user($user); break;
            case 'delete':
                $back = $this->session->userdata('users_back_link');
                if(empty($back)) $back = site_url('admin/users'); 

                if($role!=0){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->session->set_flashdata('action_ok', 'Información eliminada correctamente.');
                $this->user_model->delete_user($user['id']);
                redirect($back);
                break;
            default: 
                if($role==0)
                    redirect(site_url('admin/users'));
                else    redirect(site_url('admin'));
        }
    }


    /*
        Users list page
    */
    private function users_list()
	{ 
        if($this->current_role()!=0)
         redirect(site_url('admin'));

         
        
       $data = array('current' => 'users', 'title'=> 'Usuarios');
       $data['users'] = $this->user_model->users(true);
       
        $this->view('users/list',$data);         
    }


    /*
        New user page
    */
    private function new_user()
	{
        $_POST = $this->input->post(); $message = '';

        if(!empty($_POST)){
            $id = $this->user_model->new_user($_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/users/edit/'.$id));
            return true;
        }

       $data = array('current' => 'users', 'current_2' => 'new', 'title'=> 'Nuevo usuario', 'sections' => $this->user_model->sections());
       $data['users'] = $this->user_model->users(true);
      
        $this->view('users/new',$data);    
    }

    /*
        Edit user page
    */
    private function edit_user($user)
	{
        $_POST = $this->input->post(); $message = '';

        if(!empty($_POST)){
            $this->user_model->edit_user($user['id'], $_POST); 
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/users'));
            return true;
        }
        
        $data = array('current' => 'users', 'title'=> 'Editar usuario', 'user' => $user, 'sections' => $this->user_model->sections());
        $data['user_sections'] = $this->user_model->user_sections($user['id']);
        
        $data['users'] = $this->user_model->users(true);
        $this->view('users/edit',$data);         
    }


    /*
        Affiliates section controller
    */
    public function affiliates($action=null, $id=null)
	{   
        $this->check_login();
       $data = array();
       
        
        if(empty($action)){  
            $get = $this->input->get();
            $back = site_url('admin/affiliates');

            $param = '';
            foreach($get as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
            if(!empty($param))  $back .= '?'.$param;
            $this->session->set_userdata(array('affiliates_back_link' => $back)); 

            $this->affiliates_list(); return true;  }

        $affiliate = $this->affiliate_model->affiliate_by_id($id);

        if(empty($affiliate)&&($action=='edit'||$action=='delete')){
            redirect(site_url('admin/affiliates')); return true;
        }
        $current_user = $this->user_model->current_user();
        $role = $current_user->role; 

        switch($action){
            case 'resetpasswords': 
                $this->affiliates_reset_passwords(); break;
            case 'new': 
                $this->new_affiliate(); break;
            case 'edit':  
                if($role!=0&&!$this->user_model->user_section_permission($affiliate['section_id'], $current_user->id)){
                    redirect(site_url('admin'));
                    return true;
                }
               $this->edit_affiliate($affiliate); break;
            case 'delete': 
                $back = $this->session->userdata('affiliates_back_link');
                if(empty($back)) $back = site_url('admin/affiliates');
                if($role!=0&&!$this->user_model->user_section_permission($affiliate['section_id'], $current_user->id)){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->affiliate_model->delete_affiliate($affiliate['id']);
                $this->session->set_flashdata('action_ok', 'Información eliminada correctamente.');
                redirect($back);
                break;
            case 'logout': 
                if($role!=0&&!$this->user_model->user_section_permission($affiliate['section_id'], $current_user->id)){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->affiliate_model->affiliate_delete_session($affiliate['id']);
                redirect(site_url('admin'));
                break;
            case 'send-pass': 
                $back = $this->session->userdata('affiliates_back_link');
                if(empty($back)) $back = site_url('admin/affiliates');

                if($role!=0&&!$this->user_model->user_section_permission($affiliate['section_id'], $current_user->id)){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->session->set_flashdata('action_ok', 'Información enviada correctamente.');
                $this->affiliates_send_pass($affiliate);
                redirect($back);
                break;
            default:
                redirect(site_url('admin/affiliates'));
        }
    }

    /*
        Affiliates list page
    */
    private function affiliates_list()
	{
        $data = array('current' => 'affiliates', 'title'=> 'Afiliados');
        if($this->current_role()!=0)
         redirect(site_url('admin/messages'));
         
        if(!empty($this->input->get('section_id'))) $section_id = $this->input->get('section_id');
        else $section_id = null;
        $current_user = $this->user_model->current_user();
   //     if($current_user->role!=0) $section_id = $current_user->section_id;
        $data['affiliates'] = $this->affiliate_model->affiliates(true, $section_id);
        $data['sections'] = $this->affiliate_model->sections();

        $this->view('affiliates/list',$data);
    }


     /*
        Affiliates reset passwords
    */
    private function affiliates_reset_passwords()
	{
        $data = array('current' => 'affiliates', 'title'=> 'Afiliados');
        if($this->current_role()!=0)
         redirect(site_url('admin/affiliates'));
        $section_id = null;
        $affiliates = $this->affiliate_model->affiliates(true, $section_id);
               
        foreach($affiliates as $affiliate){
            $this->affiliate_model->update_affiliate_pass($affiliate['id']);
        }
        redirect(site_url('admin/affiliates'));
    }

     /*
        Send pass to affiliate
    */
    private function affiliates_send_pass($affiliate)
	{
       
        $pass = $this->affiliate_model->get_affiliate_pass($affiliate['id']);
        
        $message = '<p>Hola '.$affiliate['name'].',</p>';
        $message .= '<p>Esta es tu clave para acceder a nuestra APP:</p>';
        $message .= '<h2>'.$pass.'</h2>';
        return $this->send_email($affiliate['email'], 'SEM - Clave de acceso a nuestra APP', $message, 'Clave de acceso a nuestra APP');
    }
    
  


    /*
        New affiliate page
    */
    private function new_affiliate()
	{
        $_POST = $this->input->post(); $message = '';
        if($this->current_role()!=0)
        redirect(site_url('admin/messages'));
        if(!empty($_POST)){
            $id = $this->affiliate_model->new_affiliate($_POST);
            $affiliate = $this->affiliate_model->affiliate_by_id($id);
            $this->affiliates_send_pass($affiliate);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/affiliates/edit/'.$id));
            return true;
        }

       $data = array('current' => 'affiliates', 'current_2' => 'new', 'title'=> 'Nuevo afiliado', 'sections' => $this->affiliate_model->sections(), 'provinces' => $this->affiliate_model->provinces());
       $data['affiliates'] = $this->affiliate_model->affiliates(true);
      
        $this->view('affiliates/new',$data);    
    }

    /*
        Edit affiliate page
    */
    private function edit_affiliate($affiliate)
	{
        $_POST = $this->input->post(); $message = '';
      

        if($this->current_role()!=0)
         redirect(site_url('admin/messages'));

        if(!empty($_POST)){
            $this->affiliate_model->edit_affiliate($affiliate['id'], $_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/affiliates/edit/'.$affiliate['id']));
            return true;
        }
    
       $data = array('current' => 'affiliates', 'title'=> 'Editar afiliado', 'affiliate' => $affiliate, 'sections' => $this->affiliate_model->sections(), 'provinces' => $this->affiliate_model->provinces(), 'pass' => $this->affiliate_model->get_affiliate_pass($affiliate['id']));
       $data['affiliates'] = $this->affiliate_model->affiliates(true);

       

        $this->view('affiliates/edit',$data);         
    }

    /*
        Messages section controller
    */
    public function messages($action=null, $id=null)
    {   
        $this->check_login();
        $data = array();
        
        if(empty($action)){  
            $get = $this->input->get();
            $back = site_url('admin/messages');

            $param = '';
            foreach($get as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
            if(!empty($param))  $back .= '?'.$param;
            $this->session->set_userdata(array('messages_back_link' => $back)); 
            
            
            $this->messages_list(); return true;  
        }

        if($action!='delete-attachment'){
            $message = $this->message_model->message($id);

            if(empty($message)&&($action=='edit'||$action=='delete')){
                redirect(site_url('admin/messages')); return true;
            }

        }else{
            $attachment = $this->message_model->message_attachment($id); 
            if(empty($attachment['message_id'])){
                redirect(site_url('admin/messages')); return true; 
            }
        }
        
        $current_user = $this->user_model->current_user();
        $role = $current_user->role; 

        switch($action){
            case 'new': 
                $this->new_message(); break;
            case 'delete-attachment':
                $this->message_model->delete_attachment($attachment['id']);
                redirect(site_url('admin/messages/edit/'.$attachment['message_id']));
            case 'add-attachment': 
                    if($role!=0&&(!empty($message['global'])||empty($message['sections'])||!$this->message_model->user_message_permission($message['id'], $current_user->id))){
                        redirect(site_url('admin'));
                        return true;
                    } 
                    $this->message_model->add_attachment($_POST);
                    $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
                    redirect(site_url('admin/messages/edit/'.$message['id']));
                break;
            case 'edit': 
                if($role!=0&&(!empty($message['global'])||empty($message['sections'])||!$this->message_model->user_message_permission($message['id'], $current_user->id))){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->edit_message($message); break;
            
            case 'delete':
                $back = $this->session->userdata('messages_back_link');
                if(empty($back)) $back = site_url('admin/messages'); 
                if($role!=0&&(!empty($message['global'])||empty($message['sections'])||!$this->message_model->user_message_permission($message['id'], $current_user->id))){
                    redirect(site_url('admin'));
                    return true;
                }
                $this->message_model->delete_message($message['id']);
                $this->session->set_flashdata('action_ok', 'Información eliminada correctamente.');
                redirect($back);
                break;
            default:
                redirect(site_url('admin/messages'));
        }
    }

    /*
        Messages list page
    */
    private function messages_list()
    {
        $_GET = $this->input->get();
        $data = array('current' => 'messages', 'title'=> 'Mensajes');
        if(!empty($_GET['section_id'])) $section_id = $_GET['section_id'];
        else $section_id = null;
        
        $current_user = $this->user_model->current_user();
        
        if($current_user->role==0) $global = true;
        else $global = false;

        if($section_id!=null&&$this->user_model->user_section_permission($section_id, $current_user->id)){
            $current_user->sections = array($section_id);
        }
        
        
        $data['messages'] = $this->message_model->messages_list(false, $current_user->sections, $global);
        $data['sections'] = $this->message_model->sections($current_user->id);
        
        $this->view('messages/list',$data);
    }

   
    /*
        New message page
    */
    private function new_message()
    {
        $_POST = $this->input->post();

        if(!empty($_POST)){
            $id = $this->message_model->new_message($_POST); 
            $message = $this->message_model->message($id);
            
            redirect(site_url('admin/messages/edit/'.$id));
            return true;
        }

        $current_user = $this->user_model->current_user();
        
        $data = array('current' => 'messages', 'current_2' => 'new', 'title'=> 'Nuevo mensaje', 'sections' => $this->message_model->sections());
        if($current_user->role!=0) $data['section_id'] = $current_user->section_id;
        $data['messages'] = $this->message_model->messages(true);
        $data['user_sections'] = $this->user_model->user_sections($current_user->id);
        $data['user'] = $current_user;
        $this->view('messages/new',$data);    
    }

    /*
        Edit message page
    */
    private function edit_message($message)
    {
        $_POST = $this->input->post();

        if(!empty($_POST)){
            $this->message_model->edit_message($message['id'], $_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/messages/edit/'.$message['id']));
            return true;
        }
        $current_user = $this->user_model->current_user();
        $data = array('current' => 'messages', 'title'=> 'Editar mensaje', 'message' => $message, 'sections' => $this->message_model->sections());
        $data['messages'] = $this->message_model->messages(true);
        $data['user_sections'] = $this->user_model->user_sections($current_user->id);
        $data['user'] = $current_user;
        $this->view('messages/edit',$data);         
    }


    /*
        Sections section controller
    */
    public function sections($action=null, $id=null)
    {   
        $this->check_login();
        if($this->current_role()!=0){
            redirect(site_url('admin'));
            return true;
        }
        $data = array();

       
        if(empty($action)){  
            $get = $this->input->get();

            $back = site_url('admin/sections');

            $param = '';
            foreach($get as $key => $val){
                if(!empty($param))  $param .= '&';
                $param.=$key.'='.urlencode($val);
            }
            if(!empty($param))  $back .= '?'.$param;
            $this->session->set_userdata(array('sections_back_link' => $back)); 

            $this->sections_list(); return true;  
        }

        $section = $this->section_model->section($id); 
        
        if(empty($section)&&($action=='edit'||$action=='delete')){
            redirect(site_url('admin/sections')); return true;
        }

        

        switch($action){
            case 'new': 
                $this->new_section(); break; 
            case 'edit': 
                $this->edit_section($section); break; 
            case 'delete': 
                $back = $this->session->userdata('sections_back_link');
                if(empty($back)) $back = site_url('admin/sections'); 

                $this->section_model->delete_section($section['id']);
                $this->session->set_flashdata('action_ok', 'Información eliminada correctamente.');
                redirect($back);
                break; 
            default:
                redirect(site_url('admin/sections'));
        }
    }

    /*
        Sections list page
    */
    private function sections_list()
    {
        $data = array('current' => 'sections', 'title'=> 'Secciones');
        $data['sections'] = $this->section_model->sections();
        $this->view('sections/list',$data);
    }

   
    /*
        New section page
    */
    private function new_section()
    {
        $_POST = $this->input->post(); 

        if(!empty($_POST)){
            $id = $this->section_model->new_section($_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/sections/edit/'.$id));
            return true;
        }

        $data = array('current' => 'sections', 'current_2' => 'new', 'title'=> 'Nueva sección');
        
        $this->view('sections/new',$data);    
    }

    /*
        Edit section page
    */
    private function edit_section($section)
    {
        $_POST = $this->input->post(); 

        if(!empty($_POST)){
            $this->section_model->edit_section($section['id'], $_POST);
            $this->session->set_flashdata('action_ok', 'Información guardada correctamente.');
            redirect(site_url('admin/sections/edit/'.$section['id']));
            return true;
        }

        $data = array('current' => 'sections', 'title'=> 'Editar sección', 'section' => $section);
        $this->view('sections/edit',$data);         
    }

    /*
        This function gets de $_GET params from the URL
    */
    private function getURLParams(){
        $get = $this->input->get();

        if(empty($get)) return '';
        print_r($get);

        $param = '';
        foreach($get as $key => $val){
            if(!empty($param))  $param .= '&';
            $param.=$key.'='.urlencode($val);
        }

        return $param;
    }

    /*
        This function checks if the user is logged in
    */
    private function check_login(){
        if(!$this->user_model->is_logged_in()){
            redirect(site_url('admin/login')); 
        }
    }

    /*
        This function checks if the user is administrator and redirects
    */
    private function check_admin(){
        $this->check_login();
        if($this->current_role()!=0){
            redirect(site_url('admin'));
        }
    }

    /*
        This function checks if the user is administrator
    */
    private function current_role(){
        $current = $this->user_model->current_user();
        return $current->role;
    }

    /*
        This function calls a view
    */
    private function view($view,$data)
    {
        if(empty($data['current'])) $data['current'] = 'home';
        if(empty($data['current_2'])) $data['current_2'] = '';
        $current_user = $this->user_model->current_user();
        $data['role'] = $current_user->role;
        $data['current_user_section_id'] = $current_user->section_id;
        $data['content'] = $this->load->view('panel/'.$view.'.php',$data, true);
        $this->load->view('panel/theme/page.php',$data);
    }
}