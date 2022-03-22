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


/* Affiliate_model.php */

class Affiliate_model extends CI_Model {
	    
    /*
       This function returns a list of all affiliates in the database
	*/
	public function affiliates($actives=false, $section_id=null){
		$this->db->select('*');
		if(!$actives)  $this->db->where('active', 1);
		if(!empty($section_id)) $this->db->where('section_id', intval($section_id));
		$q = $this->db->get('affiliates');
		  
		$affiliates = $q->result_array();

		

		for($i=0;$i<sizeof($affiliates);$i++){
			$affiliates[$i]['has_session'] = $this->affiliate_has_session($affiliates[$i]['id']);
			$affiliates[$i]['section_name'] = '';
            $affiliates[$i]['province_name'] = '';
			if(!empty($affiliates[$i]['section_id'])){
				$this->db->select('id, name');
				$this->db->where('id', $affiliates[$i]['section_id']);
				$q = $this->db->get('sections');
				$section = $q->result_array();
				if(!empty($section[0]['id'])){
					$affiliates[$i]['section_name'] = $section[0]['name']; 
				}
			}
            if(!empty($affiliates[$i]['province_id'])){
				$this->db->select('id, name');
				$this->db->where('id', $affiliates[$i]['province_id']);
				$q = $this->db->get('provinces');
				$province = $q->result_array();
				if(!empty($province[0]['id'])){
					$affiliates[$i]['province'] = $province[0]['name']; 
				}
			}
		}
		return $affiliates;
	}


    /* 
        This function returns the data of a specific affiliate
    */
	public function affiliate($email){
		return $this->affiliate_by(array('email' => $email));
	}


    /* 
        This function returns the data of a specific affiliate by passing the id
    */
	public function affiliate_by_id($id){
		return $this->affiliate_by(array('id' => intval($id)));
   	}

    public function affiliate_by_pass($pass){
		return $this->affiliate_by(array('pass' => $pass));
   	}

    /* 
        This function returns the data of a specific affiliate by passing a specific parameter
    */
	public function affiliate_by($array){
		$this->db->select('*');
	   	$q = $this->db->get_where('affiliates', $array);
		$affiliates = $q->result_array(); 
        
		if(is_array($affiliates)&&sizeof($affiliates)>0){
            $affiliate = $affiliates[0]; 
            
            $affiliate['section_name'] = '';
            $affiliate['province_name'] = '';
			if(!empty($affiliate['section_id'])){
				$this->db->select('id, name');
				$this->db->where('id', $affiliate['section_id']);
				$q = $this->db->get('sections');
				$section = $q->result_array();
				if(!empty($section[0]['id'])){
					$affiliate['section_name'] = $section[0]['name']; 
				}
			}
            if(!empty($affiliate['province_id'])){
				$this->db->select('id, name');
				$this->db->where('id', $affiliate['province_id']);
				$q = $this->db->get('provinces');
				$province = $q->result_array();
				if(!empty($province[0]['id'])){
					$affiliate['province'] = $province[0]['name']; 
				}
			}
			$affiliate['has_session'] = $this->affiliate_has_session($affiliate['id']);
            return $affiliate;
        }
	   	return null;
	}


    /* 
        This function indicates if a specific affiliate exists
    */ 
	public function affiliate_exists($email, $affiliate_id=null){
		$array = array('email' => $email);
		if(!empty($affiliate_id))	$array['id <> '] = intval($affiliate_id);
		return $this->affiliate_exists_by($array);
	}

	public function encode_pass($pass){
		return base64_encode($pass);
	}

	public function get_affiliate_pass($id){
		
		$affiliate = $this->affiliate_by_id($id);

		if(empty($affiliate['id']))	return null;

		return $pass = $this->encode_pass($affiliate['id'].'-'.$affiliate['id_card']);
	}

	public function update_affiliate_pass($id){
		
		$affiliate = $this->affiliate_by_id($id);

		if(empty($affiliate['id']))	return null;

		$pass = $this->encode_pass($affiliate['id'].'-'.$affiliate['id_card']);
		
		$this->db->set(array('pass' => $pass));
		$this->db->where('id', intval($id));
		$this->db->update('affiliates');

	}


    /* 
        This function indicates if a affiliate exists by passing the id
    */
	public function affiliate_exists_id($id){
		return $this->affiliate_exists_by(array('id' => intval($id)));
	}

    
    /* 
        This function indicates if a affiliate exists by passing a specific parameter
    */
	public function affiliate_exists_by($array){
		$this->db->select('*');
	   	$q = $this->db->get_where('affiliates', $array);
	   	$affiliates = $q->result_array();
	   	if(is_array($affiliates)&&sizeof($affiliates)>0)	return true;
	   	else return false;
	}

	/* 
        This function indicates if an email address is available when editing a affiliate
    */
	public function affiliate_exists_edit($affiliate_id, $email){
		$sql = 'SELECT * FROM affiliates WHERE id<>'.intval($affiliate_id).' AND email="'.$email.'";';
		$q = $this->db->query($sql);
		$resultados = $q->result_array();
		if(empty($resultados)) return false;
		else return true;
	}
	
	/*
       This function returns a list of all sections in the database
    */
	public function sections(){
		$this->db->select('id, name');
		$this->db->order_by('name', 'ASC');
	   	$q = $this->db->get('sections');
	   	return $q->result_array();
	}

	/*
       This function returns a list of all provinces in the database
    */
	public function provinces(){
		$this->db->select('id, name');
		$this->db->order_by('name', 'ASC');
	   	$q = $this->db->get('provinces');
	   	return $q->result_array();
	}
	
	
	/* 
        This function creates a new affiliate
    */
	public function new_affiliate($data){
		$affiliate = array(); $data = (array) $data;
		if(empty($data['email'])||empty($data['name']))
			return false;
		
		if($this->affiliate_exists($data['email']))
			return false;
		
		if(empty($data['active'])) $data['active'] = 0;
		else $data['active'] = 1;
		
		$affiliate['active'] = $data['active'];
		$affiliate['name'] = $data['name'];
		$affiliate['surnames'] = $data['surnames'];
		$q = $this->db->query('SELECT MAX(number) as last FROM affiliates;');
		$result = $q->result_array();
		if(!empty($result[0])&&!empty($result[0]['last']))	$number = intval($result[0]['last']) + 1;
		else	$number = 1;

		$affiliate['number'] = $number;

		$affiliate['id_card'] = $data['id_card'];
		$affiliate['section_id'] = $data['section_id'];

		if(strpos($data['birthdate'], '/')>0)  $data['birthdate'] = $this->formatSQLDate($data['birthdate']);
		if(!empty(strtotime($data['birthdate']))) $affiliate['birthdate'] = $data['birthdate'];
		if(strpos($data['affiliation_date'], '/')>0)  $data['affiliation_date'] = $this->formatSQLDate($data['affiliation_date']);
		if(!empty(strtotime($data['affiliation_date']))) $affiliate['affiliation_date'] = $data['affiliation_date'];
	
		/*	$affiliate['renovation_date'] = $data['renovation_date'];*/
		$affiliate['email'] = $data['email'];
		$affiliate['phone'] = $data['phone'];
		if(!empty($data['job'])) $affiliate['job'] = $data['job'];
		if(!empty($data['job_position'])) $affiliate['job_position'] = $data['job_position'];
		if(!empty($data['address'])) $affiliate['address'] = $data['address'];
		if(!empty($data['zipcode'])) $affiliate['zipcode'] = $data['zipcode'];
		if(!empty($data['location'])) $affiliate['location'] = $data['location'];
		if(!empty($data['province_id'])) $affiliate['province_id'] = $data['province_id'];

		
        $affiliate['pass'] = '';

		$this->db->insert('affiliates', $affiliate);
		
		$id = intval($this->db->insert_id());
		
		$this->update_affiliate_pass($id);

		return $id;
	}

	/* 
        This function changes the format of a date
    */
	public function formatSQLDate($date){
        if(empty($date)||strlen($date)!=10) return '';

        $date = explode('/', $date);
        if(!is_array($date)||sizeof($date)!=3)  return '';
		
		$date = $date[2].'-'.$date[1].'-'.$date[0];

        if(empty(strtotime($date))) return '';
		
		return $date;
    }

	
	/* 
        This function edits a specific affiliate
	*/ 
	public function edit_affiliate($id, $data){
		$affiliate = array(); $data = (array) $data;

		$affiliate_data = $this->affiliate_by_id($id); 
		if(empty($affiliate_data['id']))	return false;

		if($this->affiliate_exists_edit($id, $data['email']))
			return false;


		if(empty($data['active'])) $affiliate['active'] = 0;
		else $affiliate['active'] = 1;
		
	
		$affiliate['name'] = $data['name'];
		$affiliate['number'] = $data['number'];
		$affiliate['surnames'] = $data['surnames'];
		$affiliate['id_card'] = $data['id_card'];
		$affiliate['section_id'] = $data['section_id'];
		
		if(strpos($data['birthdate'], '/')>0)  $data['birthdate'] = $this->formatSQLDate($data['birthdate']);
		if(!empty(strtotime($data['birthdate']))) $affiliate['birthdate'] = $data['birthdate'];
		if(strpos($data['affiliation_date'], '/')>0)  $data['affiliation_date'] = $this->formatSQLDate($data['affiliation_date']);
		if(!empty(strtotime($data['affiliation_date']))) $affiliate['affiliation_date'] = $data['affiliation_date'];
	 
	
		
		$affiliate['email'] = $data['email'];
		$affiliate['phone'] = $data['phone'];
		if(!empty($data['job'])) $affiliate['job'] = $data['job'];
		if(!empty($data['job_position'])) $affiliate['job_position'] = $data['job_position'];
		if(!empty($data['address'])) $affiliate['address'] = $data['address'];
		if(!empty($data['zipcode'])) $affiliate['zipcode'] = $data['zipcode'];
		if(!empty($data['location'])) $affiliate['location'] = $data['location'];
		if(!empty($data['province_id'])) $affiliate['province_id'] = $data['province_id'];

		if(empty($affiliate)) return true;

		$this->db->set($affiliate);
		$this->db->where('id', intval($id));
		$this->db->update('affiliates');
		
		$this->update_affiliate_pass($id);

		return true;
	}
	

	/*
		This function activates a affiliate
	*/
	public function activate_affiliate($id){
		if(!$this->affiliate_exists_id($id)) return false;

		$this->db->set(array('active' => 1));
		$this->db->where('id', intval($id));
		$this->db->update('affiliates');
		return true;
	}


	/*
		This function deletes a affiliate
	*/
	public function delete_affiliate($id){
		$this->affiliate_delete_session($id);
		$this->db->delete('affiliates', array('id' => intval($id)));
	}

	public function login($affiliate){
		$this->load->library('session'); 
		if(empty($affiliate['id']))	return false;
		if(empty($affiliate['active']))	return false;
		
		$data = array();
		$data['affiliate'] = $affiliate = (object) $affiliate;
		unset($affiliate->pass);

		$this->db->select('*');
		$q = $this->db->get_where('sessions', array('affiliate_id' => $affiliate->id));
		$sessions = $q->result_array();
		if(!empty($sessions)&&is_array($sessions)){
			$data['session'] = $sessions[0];
			if($affiliate->role==0){
				//$this->session->sess_destroy();
				$this->session->set_affiliatedata($data);
			}
			return $data;
		}   
       
        $time = time();
		$data['session'] = array(
			'session_id' => base64_encode($affiliate->id.'-'.$time.'-session'),
			'affiliate_id' => $affiliate->id,
			'creation_time' => $time
		);

		$this->db->insert('sessions', $data['session']);
		
		return $data;
	}
	
	
	/*
		This function returns the data of the current affiliate
	*/
	public function current_affiliate(){
		$this->load->library('session'); 
		if(!$this->is_logged_in()) return null;
		else return $this->session->affiliatedata('affiliate');
	}

	
	/*
		This function returns the id of the current affiliate
	*/
	public function current_affiliate_id(){
		$this->load->library('session'); 
		if(!$this->is_logged_in()) return null;
		else{
			$affiliate = $this->session->affiliatedata('affiliate');
			return $affiliate->id;
		} 
	}


	/*
		This function opens a affiliate session by passing its email as a parameter
	*/
	public function login_affiliate($email){
		return $this->login($this->affiliate($email));
	}

	
	/*
		This function opens a affiliate session by passing its id as a parameter
	*/
	public function login_affiliate_id($id){
		return $this->login($this->affiliate_by_id($id));
	}


	/*
		This function indicates if there is a affiliate session open
	*/
	public function is_logged_in(){
		$this->load->library('session');
		$session = $this->session->affiliatedata('session');
		if(empty($session['session_id'])) return false;
		else return true; 
	}

	/*
		This function closes the affiliate session
	*/
	public function logout($session_id=null){
		$this->load->library('session');
		if(!empty($session_id))
			$this->db->query('DELETE FROM sessions WHERE session_id="'.$session_id.'";');
		$this->session->sess_destroy();
		unset($_SESSION['session']);
		unset($_SESSION['affiliate']);
	}


	/*
		This function indicates if a session exists
	*/
	public function session_exists($session_id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sessions', array('session_id' => $session_id));
	   	$result = $q->result_array();
	   	if(is_array($result)&&sizeof($result)>0)	return true;
	   	else return false;
	}

    public function affiliate_has_session($affiliate_id){
        $this->db->select('*');
	   	$q = $this->db->get_where('sessions', array('affiliate_id' => $affiliate_id));
        $result = $q->result_array();
	   	if(is_array($result)&&sizeof($result)>0)	return true;
	   	else return false;
    }

	public function affiliate_delete_session($affiliate_id){
        $this->db->delete('sessions', array('affiliate_id' => intval($affiliate_id)));
		return true;
    }


	/*
		This function returns the affiliate data of a session
	*/
	public function affiliate_session($session_id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sessions', array('session_id' => $session_id));
	   	$result = $q->result_array();
		   if(is_array($result)&&sizeof($result)>0)	return intval($result[0]['affiliate_id']);
		else return null;
	}


	/*
		This function returns the current session id
	*/
	public function session_id(){
		$this->load->library('session');
		$session = $this->session->affiliatedata('session');
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