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


/* User_model.php */

class User_model extends CI_Model {
	    
    /*
       This function returns a list of all users in the database
	*/
	public function users($actives=false){
		$this->db->select('id, email, name, role, section_id, active');
		if(!$actives)  $this->db->where('active', 1);
		$q = $this->db->get('users');
		$users = $q->result_array();

		for($i=0;$i<sizeof($users);$i++){
			$users[$i]['sections_string'] =  $this->user_sections_string($users[$i]['id']);
		}
		return $users;
	}

	    
    /*
       This function returns a list of all administrators in the database
    */
	public function administrators(){
		$this->db->select('id, email, name');
	   $q = $this->db->get_where('users', array('role' => 0));
	   return $q->result_array();
	}

	/*
       This function returns a list of all sections in the database
    */
	public function sections(){
		$this->db->select('id, name');
	   $q = $this->db->get('sections');
	   return $q->result_array();
	}

	/*
       This function returns a section in the database
    */
	public function section($id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sections', array('id' => intval($id)));
		$sections = $q->result_array(); 
		if(is_array($sections)&&sizeof($sections)>0)	return $sections[0];
	   	else return null;
	}
	
	    
    /*
       This function returns a list of all users without administrative permission in the database
    */
	public function app_users(){
		$this->db->select('id, email, name');
	   $q = $this->db->get_where('users', array('role' => 1));
	   return $q->result_array();
   	}
    
    /* 
        This function returns the data of a specific user
    */
	public function user($email){
		return $this->user_by(array('email' => $email));
	}


    /* 
        This function returns the data of a specific user by passing the id
    */
	public function user_by_id($id){
		return $this->user_by(array('id' => intval($id)));
   	}

    /* 
        This function returns the data of a specific user by passing a specific parameter
    */
	public function user_by($array){
		$this->db->select('*');
	   	$q = $this->db->get_where('users', $array);
		$users = $q->result_array(); 
		if(is_array($users)&&sizeof($users)>0)	return $users[0];
	   	else return null;
	}


    /* 
        This function indicates if a specific user exists
    */ 
	public function user_exists($email, $user_id=null){
		$array = array('email' => $email);
		if(!empty($user_id))	$array['id <> '] = intval($user_id);
		return $this->user_exists_by($array);
	}


    /* 
        This function indicates if a user exists by passing the id
    */
	public function user_exists_id($id){
		return $this->user_exists_by(array('id' => intval($id)));
	}

    
    /* 
        This function indicates if a user exists by passing a specific parameter
    */
	public function user_exists_by($array){
		$this->db->select('*');
	   	$q = $this->db->get_where('users', $array);
	   	$users = $q->result_array();
	   	if(is_array($users)&&sizeof($users)>0)	return true;
	   	else return false;
	}

	/* 
        This function indicates if an email address is available when editing a user
    */
	public function user_exists_edit($user_id, $email){
		$sql = 'SELECT * FROM users WHERE id<>'.intval($user_id).' AND email="'.$email.'";';
		$q = $this->db->query($sql);
		$resultados = $q->result_array();
		if(empty($resultados)) return false;
		else return true;
	}
	
	
	/* 
        This function creates a new user
    */
	public function new_user($data){
		$user = array(); $data = (array) $data;
		if(empty($data['email'])||empty($data['name'])||empty($data['pass']))
			return false;
		
		if($this->user_exists($data['email']))
			return false;
		
		if(empty($data['active'])) $user['active'] = 0;
		else $user['active'] = 1;
		
		$user['email'] = $data['email'];
		$user['pass'] = md5($data['pass']);
		$user['name'] = $data['name'];
		if(!empty($data['role'])) $user['role'] = $data['role'];
		else $user['role'] = 0;

		if(!empty($data['section_id'])) $user['section_id'] = $data['section_id'];

		$user['creation_date'] = date('Y-m-d H:i:s');

		$this->db->insert('users', $user);
		$insert_id = $this->db->insert_id();
		$this->update_user_sections($insert_id, $data['sections']);
		return true;
	}

	
	/* 
        This function edits a specific user
	*/ 
	public function edit_user($id, $data){
		$user = array(); $data = (array) $data;

		$user_data = $this->user_by_id($id); 
		if(empty($user_data['id']))	return false;

		if($this->user_exists_edit($id, $data['email']))
			return false;

		
		if(empty($data['active'])) $user['active'] = 0;
		else $user['active'] = 1;
		
		if(empty($data['pass'])) unset($data['pass']);

		$user['role'] = $data['role'];
		if($user['role']==0) $data['sections'] = array();

		if(empty($data['sections'])||is_array($data['sections'])){
			$this->update_user_sections($id, $data['sections']);
		}

		if(!empty($data['email'])) $user['email'] = $data['email'];
		if(!empty($data['pass']))	$user['pass'] = md5($data['pass']);
		if(!empty($data['name'])) $user['name'] = $data['name'];
		
		if(empty($user)) return true;

		$this->db->set($user);
		$this->db->where('id', intval($id));
		$this->db->update('users');
		
		return true;
	}

	/* 
        This function edits the sections of a specific user
	*/ 
	public function update_user_sections($user_id, $sections){
		$this->db->delete('user_sections', array('user_id' => intval($user_id)));

		if(empty($sections)||!is_array($sections)) return true;

		foreach($sections as $section)
			$this->db->insert('user_sections', array('user_id' => intval($user_id), 'section_id' => intval($section)));
		
		
		return true;
	}


	/* 
        This function determines whether or not a user can access a certain section
	*/ 
	public function user_section_permission($section_id, $user_id){
		if(empty($user_id)||empty($section_id)) return false;
		$user = (object) $this->user_by_id($user_id);
		if(empty($user->id)) return false;

		if($user->role==0) return true;

		$sql = 'SELECT section_id FROM user_sections WHERE user_id='.intval($user_id).' AND section_id='.intval($section_id).';';
		$q = $this->db->query($sql);
		$resultados = $q->result_array();
		if(empty($resultados)) return false;
		else return true;
		
	}	

	

	/* 
        This function returns the list of sections that a user has access to
	*/ 
	public function user_sections($user_id){ 
		if(empty($user_id)) return false;
		$user = (object) $this->user_by_id($user_id);
		if(empty($user->id)) return false;

		if($user->role==0){
			$sql = 'SELECT section_id FROM user_sections;';
			$q = $this->db->query($sql);
			$resultados = $q->result_array();
		}else{
			$sql = 'SELECT section_id FROM user_sections WHERE user_id='.intval($user_id).';';
			$q = $this->db->query($sql);
			$resultados = $q->result_array();
		}
	
		$sections = array();
		if(empty($resultados)) return $sections;

		foreach($resultados as $resultado){
			array_push($sections, $resultado['section_id']);
		}

		return $sections;
	}	

	/* 
        This function returns the list of sections that a user has access to in a string
	*/ 
	public function user_sections_string($user_id){ 
		if(empty($user_id)) return false;
		$user = (object) $this->user_by_id($user_id);
		if(empty($user->id)) return false;

		if($user->role==0){
			return 'Todas';
		}else{
			$sql = 'SELECT s.* FROM user_sections us, sections s WHERE us.section_id=s.id AND us.user_id='.intval($user_id).';';
			$q = $this->db->query($sql);
			$resultados = $q->result_array();

			if(empty($resultados)) return '';

			$str = '';
			foreach($resultados as $item):
				if($str!='') $str .= ', ';
				$str .= $item['name'];
			endforeach;
			return $str;
		}

		return $sections;
	}	


	/*
		This function changes the password of a user
	*/
	public function change_pass($id, $pass){
		if(!$this->user_exists_id($id)) return false;

		$this->db->set(array('pass' => md5($pass)));
		$this->db->where('id', intval($id));
		$this->db->update('users');

		return true;
	}
	

	/*
		This function activates a user
	*/
	public function activate_user($id){
		if(!$this->user_exists_id($id)) return false;

		$this->db->set(array('active' => 1));
		$this->db->where('id', intval($id));
		$this->db->update('users');
		return true;
	}


	/*
		This function deletes a user
	*/
	public function delete_user($id){
		$this->db->delete('users', array('id' => intval($id)));
	}

	/* This function creates a session for an user */
	
	public function login($user){ 
		$this->load->library('session'); 
		if(empty($user['id']))	return false;
		if(empty($user['active']))	return false;
		$data = array();
		$data['user'] = $user = (object) $user;
		unset($user->pass);

		$time = time();
		$data['session'] = array(
			'session_id' => base64_encode($user->id.'-'.$time.'-session'),
			'user_id' => $user->id,
			'creation_time' => $time
		);

		//if(intval($user->role)==0){
			//$this->session->sess_destroy();
			$this->session->set_userdata($data);
			
		//}

		
		return $data;
	}


	/*
		This function returns the data of the current user
	*/
	public function current_user(){
		$this->load->library('session'); 
		if(!$this->is_logged_in()) return null;
		$user = $this->session->userdata('user');
		$user = (object) $this->user_by_id($user->id);
		$user->sections = $this->user_sections($user->id);
		
		return $user;
	}

	
	/*
		This function returns the id of the current user
	*/
	public function current_user_id(){
		$this->load->library('session'); 
		if(!$this->is_logged_in()) return null;
		else{
			$user = $this->session->userdata('user');
			return $user->id;
		} 
	}


	/*
		This function opens a user session by passing its email as a parameter
	*/
	public function login_user($email){
		return $this->login($this->user($email));
	}

	
	/*
		This function opens a user session by passing its id as a parameter
	*/
	public function login_user_id($id){
		return $this->login($this->user_by_id($id));
	}


	/*
		This function indicates if there is a user session open
	*/
	public function is_logged_in(){
		$this->load->library('session');
		$session = $this->session->userdata('session');
		if(empty($session['session_id'])) return false;
		else	return true; 
		
	}

	/*
		This function closes the user session
	*/
	public function logout($session_id=null){
		$this->load->library('session');
	//	if(!empty($session_id))
	//		$this->db->query('DELETE FROM sessions WHERE session_id="'.$session_id.'";');
		$this->session->sess_destroy();
		unset($_SESSION['session']);
		unset($_SESSION['user']);
	}


	/*
		This function indicates if a session exists
	*/
	public function session_exists($session_id){
	//	$this->db->select('*');
	// 	$q = $this->db->get_where('sessions', array('session_id' => $session_id));
	// 	$result = $q->result_array();
	   	if(is_array($result)&&sizeof($result)>0)	return true;
	   	else return false;
	}


	/*
		This function returns the user data of a session
	*/
/*	public function user_session($session_id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sessions', array('session_id' => $session_id));
	   	$result = $q->result_array();
		   if(is_array($result)&&sizeof($result)>0)	return intval($result[0]['user_id']);
		else return null;
	}
*/

	/*
		This function returns the current session id
	*/
	public function session_id(){
		$this->load->library('session');
		$session = $this->session->userdata('session');
		if(!empty($session['session_id']))	 return $session['session_id'];
		else return null; 
	}

	
	/*	
		This function indicates if an email address is valid
	*/
    public function email_validation($email) { 
		if(strpos($email, ' ')>0) return false;
        return (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) ? false : true; 
	}

}