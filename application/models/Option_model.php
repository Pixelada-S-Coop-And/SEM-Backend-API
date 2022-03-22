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

/* Option_model.php */

class Option_model extends CI_Model {
    
    /*
       This function returns a list of all options in the database
    */
    public function options(){
        $this->db->select('*');
        $q = $this->db->get('options');
        return $q->result_array();
    }

    
    /* 
        This function returns the value of a specific option
    */    
    public function option($key, $default=null){
        $this->db->select('*');
	   	$q = $this->db->get_where('options', array('key' => $key));
		$option = $q->row(); 
        if(!empty($option->value))
            return $option->value;
        else return $default;
    }


    /* 
        This function indicates if a specific option exists
    */    
    public function option_exists($id){
		$this->db->select('*');
	   	$q = $this->db->get_where('options', array('id' => $id));
        $option = $q->row(); 
		if(!empty($option->id))	return true;
	   	else return false;
    }

    /* 
        This function indicates if a specific option exists by key
    */    
    public function option_exists_by_key($key){
		$this->db->select('*');
	   	$q = $this->db->get_where('options', array('key' => $key));
        $option = $q->row(); 
		if(!empty($option->key))	return true;
	   	else return false;
    }

    /* 
        This function creates a new option
    */   
    public function new_option($key, $value){
        if($this->option_exists_by_key($key)) $this->set_option($key, $value);
        
        $option = array('key' => $key, 'value' => $value);
        
        $this->db->insert('options', $option);

        $insert_id = $this->db->insert_id();

        return $insert_id;
    }


    /* 
        This function edits a specific option
    */    
    public function set_option($key, $value){
        if(empty($key)) return false;

        if(!$this->option_exists_by_key($key))    $this->new_option($key, $value);

        $option = array('key' => $key, 'value' => $value);

        $this->db->set($option);
		$this->db->where('key', $key);
        $this->db->update('options');
        
		return true;
    }

    /* 
        This function edits a group a options
    */    
    public function save_options($options){
        if(empty($options)||!is_array($options)) return false;
        foreach($options as $key => $value):
            if(!$this->option_exists_by_key($key))    $this->new_option($key, $value);
            $this->db->set(array('key' => $key, 'value' => $value));
            $this->db->where('key', $key);
            $this->db->update('options');
        
        endforeach;
		return true;
    }

    /* 
        This function returns the current token
    */    
    public function get_token(){
        return $token = $this->option('token', '_____'.md5('Autism0*'));
    }

}