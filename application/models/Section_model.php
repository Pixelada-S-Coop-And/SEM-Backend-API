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


/* Section_model.php */

class Section_model extends CI_Model {
	    
    /*
       This function returns a list of all sections in the database
	*/
	public function sections(){
		$this->db->select('*');
		$q = $this->db->get('sections');
		$sections = $q->result_array();

	
		return $sections;
	}
    
   
    /* 
        This function returns the data of a specific section by passing a specific parameter
    */
	public function section($id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sections', array('id' => intval($id)));
		$sections = $q->result_array(); 
		if(is_array($sections)&&sizeof($sections)>0)	return $sections[0];
	   	else return null;
	}


    /* 
        This function indicates if a specific section exists
    */ 
	public function section_exists($id){
		$this->db->select('*');
	   	$q = $this->db->get_where('sections', array('id' => intval($id)));
	   	$sections = $q->result_array();
	   	if(is_array($sections)&&sizeof($sections)>0)	return true;
	   	else return false;
	}
	
	
	/* 
        This function creates a new section
    */
	public function new_section($data){
		$section = array(); $data = (array) $data;
		if(empty($data['name'])) return false;
		
		$section['name'] = $data['name'];
		if(!empty($data['description'])) $section['description'] = $data['description'];

		$this->db->insert('sections', $section);

		$insert_id = $this->db->insert_id();

		return $insert_id;
	}

	
	/* 
        This function edits a specific section
	*/ 
	public function edit_section($id, $data){
		$section = array(); $data = (array) $data;

		$section_data = $this->section($id); 
		if(empty($section_data['id']))	return false;

		
		$section['name'] = $data['name'];
		if(!empty($data['description'])) $section['description'] = $data['description'];
		
		if(empty($section)) return true;

		$this->db->set($section);
		$this->db->where('id', intval($id));
		$this->db->update('sections');
		
		return true;
	}


	/*
		This function deletes a section
	*/
	public function delete_section($id){
		$this->db->delete('sections', array('id' => intval($id)));
	}


}