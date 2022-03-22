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


/* Notificacion_model.php */

class Message_model extends CI_Model {
	    
    /*
       This function returns a list of all messages in the database
	*/
	public function messages($section_id, $last_time=null){
		if(empty($last_time))   $last_time = '1970-01-01 00:00:00';
        $current_time = date('Y-m-d H:i:s');

		$sql = 'SELECT * FROM messages WHERE time <= "'.$current_time.'" AND expiration_time > "'.$current_time.'" AND time > "'.$last_time. '" AND active = 1 AND (global = 1 OR id IN (SELECT message_id id FROM section_messages WHERE section_id = '.intval($section_id).')) ORDER BY time ASC;';
		
		$q = $this->db->query($sql);
		$messages = $q->result_array();
		if(empty($messages)) return null;

		for($i=0;$i<sizeof($messages);$i++){
			if(!empty($messages[$i]['time']))	 $messages[$i]['time'] = strtotime($messages[$i]['time']);
			if(!empty($messages[$i]['expiration_time'])) $messages[$i]['expiration_time'] = strtotime($messages[$i]['expiration_time']);
			$this->db->select('id, title, url, order');
            $this->db->where('message_id', $messages[$i]['id']);
			$this->db->order_by('order', 'ASC');
            $q = $this->db->get('message_attachments');
            $messages[$i]['attachments'] = $q->result_array();
			$messages[$i]['sections'] = $this->message_sections($messages[$i]['id']);
			
			if($messages[$i]['global']==1)	$messages[$i]['target'] = 'Global';
			else{
				if(empty($messages[$i]['sections'])||!is_array($messages[$i]['sections']))	$messages[$i]['target'] = '';
				else{
					$messages[$i]['target'] = '';
					foreach($messages[$i]['sections'] as $section_id):
						if(!empty($messages[$i]['target']))	$messages[$i]['target'] .= ', ';
						$section = $this->section($section_id) ;
						$messages[$i]['target'] .= $section['name'];
					endforeach;
				}
			}
		}
		return $messages;
	}

	/*
       This function returns a list of all messages in the database
	*/
	public function messages_list($active=false, $sections, $global=true){
		if(empty($last_time))   $last_time = '1970-01-01 00:00:00';
        $current_time = date('Y-m-d H:i:s');

		$sql = 'SELECT * FROM messages'; // 
		$where = '';
		if(!empty($active))
			$where = ' active = 1 ';
		
		if((!empty($sections)||$global)){ 
			if(!empty($where))	$where .= ' AND ' ;
			$where .= ' ( ';
		}
		
		if(!empty($sections)){
			
			$where .= '  (id IN (SELECT message_id id FROM section_messages WHERE ( ';
			$or = '';
			foreach($sections as $section_id):
				if($or!='') $or.= ' OR ';
				$or .= ' section_id = '.intval($section_id);
			endforeach;
			$where .= $or;
			
			$where .= ')))';
		}
		if($global){
			if(!empty($sections)) $where .= ' OR ';
			$where .= ' global=1 ';
		}

		if((!empty($sections)||$global)){
			$where .= ' ) ';
		}

		if(!empty($where)) $where = ' WHERE '.$where;

		$sql .= $where.' ORDER BY time DESC'; 

		$q = $this->db->query($sql);
		$messages = $q->result_array();
		if(empty($messages)) return null;

		for($i=0;$i<sizeof($messages);$i++){
			if(!empty($messages[$i]['time']))	 $messages[$i]['time'] = strtotime($messages[$i]['time']);
			if(!empty($messages[$i]['expiration_time'])) $messages[$i]['expiration_time'] = strtotime($messages[$i]['expiration_time']);
			$this->db->select('id, title, url, order');
            $this->db->where('message_id', $messages[$i]['id']);
			$this->db->order_by('order', 'ASC');
            $q = $this->db->get('message_attachments');
            $messages[$i]['attachments'] = $q->result_array();
			$messages[$i]['sections'] = $this->message_sections($messages[$i]['id']);
			
			if($messages[$i]['global']==1)	$messages[$i]['target'] = 'Global';
			else{
				if(empty($messages[$i]['sections'])||!is_array($messages[$i]['sections']))	$messages[$i]['target'] = '';
				else{
					$messages[$i]['target'] = '';
					foreach($messages[$i]['sections'] as $section_id):
						if(!empty($messages[$i]['target']))	$messages[$i]['target'] .= ', ';
						$section = $this->section($section_id) ;
						$messages[$i]['target'] .= $section['name'];
					endforeach;
				}
			}
		}

		return $messages;
	}
	
	/*
       This function returns a messages in the database by its id
	*/
	public function message($id){
		$this->db->select('*');
		$this->db->where('id', intval($id));
        $q = $this->db->get('messages');
		$messages = $q->result_array();
		
		if(!empty($messages)&&!empty($messages[0])&&!empty($messages[0]['id'])){
			$message = $messages[0];
		}else return null;

		if(!empty($message['time']))	$message['time'] = strtotime($message['time']);
		if(!empty($message['expiration_time']))	$message['expiration_time'] = strtotime($message['expiration_time']);
		
		$this->db->select('id, title, url, order');
		$this->db->where('message_id', $message['id']);
		$this->db->order_by('order', 'ASC');
		$q = $this->db->get('message_attachments');
		$message['attachments'] = $q->result_array();
		$message['sections'] = $this->message_sections($message['id']);
		if($message['global']==1)	$message['target'] = 'Global';
		else{
			if(!empty($message['sections'])||!is_array($message['sections']))	$message['target'] = '';
			else{
				$message['target'] = '';
				foreach($message['sections'] as $section_id):
					if(!empty($message['target']))	$message['target'] .= ', ';
					$section = $this->section($section_id) ;
					$message['target'] .= $section['name'];
				endforeach;
			}
		}
		return $message;
	}

	/*
       This function returns an attachment in the database by its id
	*/
	public function message_attachment($id){
		$this->db->select('*');
		$this->db->where('id', intval($id));
        $q = $this->db->get('message_attachments');
		$result = $q->result_array();
		
		if(!empty($result)&&!empty($result[0])&&!empty($result[0]['id'])){
			return $result[0];
		}else return null;
	}

	/*
       Inserts an attachment in the database
	*/
	public function add_attachment($data){ 
		if(empty($data['message_id'])||!$this->message_exists(intval($data['message_id']))) return false;
		$data['message_id'] = intval($data['message_id']);
		if(empty($data['attachment-type'])){
			$file = site_url($this->upload_file('file'));
			if(empty($file))	return false;
			$data['url'] = $file;
			unset($data['attachment-url']);
		}else{
			$data['url'] = $data['attachment-url'];
			unset($data['attachment-url']);
			unset($data['attachment-type']);
		}
		
		$this->db->insert('message_attachments', $data);
	}

	/* 
        This function uploads a file
    */
    private function upload_file($file){
        if(empty(basename($_FILES[$file]['name']))) return null;
        $upload_path = BASEPATH.'/../uploads/';

        if(!file_exists($upload_path)) mkdir(($upload_path));
		$upload_url = 'uploads/';
		$info = pathinfo($_FILES[$file]['name']); 
        $file_path = $upload_path . strtoslug($info['filename']).'.'.$info['extension'];

        if(file_exists($file_path)){ 
            $info = pathinfo($file_path);  $i=1;
            while(file_exists($file_path)){
                $file_path = $upload_path.strtoslug($info['filename']).'-'.$i.'.'.$info['extension'];
                $i++;
            }
        }
        if (move_uploaded_file($_FILES[$file]['tmp_name'], $file_path)) 
            return $upload_url.basename($file_path);
        return null;        
    }

	/*
       This function returns a messages in the database by its id
	*/
	public function message_sections($message_id){
		$this->db->select('section_id');
		$this->db->where('message_id', intval($message_id));
        $q = $this->db->get('section_messages');
		$sections = $q->result_array();
		
		if(empty($sections[0]['section_id'])) return null;
		$result = array();

		foreach($sections as $section)
			array_push($result, $section['section_id']);

		return $result;
	}

	/* 
        This function creates a new message
    */
	public function new_message($data){
		$message = array(); $data = (array) $data;
		
		if(empty($data['active'])) $message['active'] = 0;
		else $message['active'] = 1;
	
		if(empty($data['global'])) $message['global'] = 0;
		else $message['global'] = 1;
		

		$message['subject'] = $data['subject'];
		$message['content'] = $data['content'];
		$message['blog_post_title'] = $data['blog_post_title'];
		$message['blog_post_url'] = $data['blog_post_url'];
		
		if(strpos($data['time'], '/')>0)  $data['time'] = $this->formatSQLDateTime($data['time']);
		if(!empty(strtotime($data['time']))) $message['time'] = $data['time'];
		if(strpos($data['expiration_time'], '/')>0)  $data['expiration_time'] = $this->formatSQLDateTime($data['expiration_time']);
		if(!empty(strtotime($data['expiration_time']))) $message['expiration_time'] = $data['expiration_time'];


/*	
		$message['time'] = $data['time'];
		if(!empty($data['expiration_time'])) 
			$message['expiration_time'] = $data['expiration_time'];
*/

		if(empty($data['sections'])||$message['global']==1)	$data['sections'] = array();
		
	
		if(empty($message)) return true;


		$this->db->insert('messages', $message);
		$insert_id = $this->db->insert_id();
		$this->update_message_sections($insert_id, $data['sections']);

        return $insert_id;

	}
	
	/* 
        This function changes the format of a date and hour
    */
	public function formatSQLDateTime($datetime){ 
        if(empty($datetime)||strlen($datetime)!=19) return '';
		
        $datetime = explode(' ', $datetime);
		if(!is_array($datetime)||sizeof($datetime)!=2)  return '';

		$date = $datetime[0];

		$date = explode('/', $date);
        if(!is_array($date)||sizeof($date)!=3)  return '';

        $date = $date[2].'-'.$date[1].'-'.$date[0];

        $datetime = $date.' '.$datetime[1];
		
		if(empty(strtotime($datetime))) return '';
		
		return $datetime;
    }
	
	/* 
        This function edits a specific message
	*/ 
	public function edit_message($id, $data){
		$message = array(); $data = (array) $data;

		$message_data = $this->message($id); 
		if(empty($message_data['id']))	return false;

		
		
		if(empty($data['active'])) $message['active'] = 0;
		else $message['active'] = 1;
		

		$message['subject'] = $data['subject'];
		$message['content'] = $data['content'];
		$message['blog_post_title'] = $data['blog_post_title'];
		$message['blog_post_url'] = $data['blog_post_url'];
		
		if(strpos($data['time'], '/')>0)  $data['time'] = $this->formatSQLDateTime($data['time']);
		if(!empty(strtotime($data['time']))) $message['time'] = $data['time'];
		if(strpos($data['expiration_time'], '/')>0)  $data['expiration_time'] = $this->formatSQLDateTime($data['expiration_time']);
		if(!empty(strtotime($data['expiration_time']))) $message['expiration_time'] = $data['expiration_time'];


/*	
		$message['time'] = $data['time'];
		if(!empty($data['expiration_time'])) 
			$message['expiration_time'] = $data['expiration_time'];
*/
		
		if(empty($data['sections'])||is_array($data['sections'])||$data['sections']!='no-edit'){
			if(empty($data['global'])) $message['global'] = 0;
			else $message['global'] = 1;
			if(empty($data['sections'])||$message['global']==1)	$data['sections'] = array();
		
			$this->update_message_sections($id, $data['sections']);
		}
	//	print_array($data); print_array($message);
		if(empty($message)) return true;

		$this->db->set($message);
		$this->db->where('id', intval($id));
		$this->db->update('messages');
		
		return true;
	}

	/* 
        This function edits the sectiopns of a specific message
	*/ 
	public function update_message_sections($message_id, $sections){
		$this->db->delete('section_messages', array('message_id' => intval($message_id)));

		if(empty($sections)||!is_array($sections)) return true;

		foreach($sections as $section)
			$this->db->insert('section_messages', array('message_id' => intval($message_id), 'section_id' => intval($section)));
		
		
		return true;
	}


	/*
		This function deletes a message
	*/
	public function delete_message($id){
		$this->db->delete('message_attachments', array('message_id' => intval($id)));
		$this->db->delete('section_messages', array('message_id' => intval($id)));
		$this->db->delete('messages', array('id' => intval($id)));
	}


	/*
		This function deletes a message
	*/
	public function delete_attachment($id){
		$this->db->delete('message_attachments', array('id' => intval($id)));
	}

	/*
       This function returns a list of all sections in the database
    */
	public function sections($user_id=null){
		if(empty($user_id)){
			$sql = 'SELECT * FROM sections';
			$q = $this->db->query($sql);
			return $resultados = $q->result_array();
		}else{ 
			$user = (object) $this->user_by_id($user_id); 
			if(empty($user->id))	return null; 
			if($user->role==0){
				$sql = 'SELECT * FROM sections';
				$q = $this->db->query($sql);
				return $resultados = $q->result_array();
			}else{
				$sql = 'SELECT s.* FROM sections s, user_sections us WHERE s.id=us.section_id AND us.user_id='.intval($user_id).';';
				$q = $this->db->query($sql);
				return $resultados = $q->result_array();
			}
		}
	}

	/*
       This function returns a list of all sections in the database
    */
	public function section($id){
		$this->db->select('id, name');
		$this->db->where('id', intval($id));
	   	$q = $this->db->get('sections');
		$result = $q->result_array();
		if(empty($result[0]['id']))	return null;
		else return $result[0];
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
        This function determines whether or not a user can access a certain section
	*/ 
	public function user_message_permission($message_id, $user_id){
		if(empty($user_id)||empty($message_id)) return false;
		$user = (object) $this->user_by_id($user_id);
		if(empty($user->id)) return false;
		$message = (object) $this->message_model->message($message_id);
		if(empty($message->id)) return false;

		if($user->role==0) return true;

		$sql = 'SELECT us.* FROM user_sections us, section_messages sm WHERE us.user_id='.intval($user_id).' AND us.section_id=sm.section_id AND sm.message_id='.intval($message_id).';';
		$q = $this->db->query($sql);
		$resultados = $q->result_array();
		if(empty($resultados)) return false;
		else return true;
		
	}	


    /* 
        This function indicates if a specific message exists
    */ 
	public function message_exists($message_id){
		$this->db->select('*');
		$this->db->where('id', intval($message_id));
        $q = $this->db->get('messages');
		$messages = $q->result_array(); 
		
		if(!empty($messages)&&!empty($messages[0])&&!empty($messages[0]['id'])){
			return true;
		}else return false;
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

}