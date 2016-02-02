<?php

class Front_office_model extends CI_Model 
{	
		/*
	*	Count all items from a table
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function count_items($table, $where, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	/*
	*	Retrieve all beds
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_residents($table, $where, $per_page, $page, $order = 'resident_no', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}


	public function get_all_buildings($table, $where, $per_page, $page, $order = 'building_name', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_all_floors($table, $where, $per_page, $page, $order = 'floor_name', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function get_all_items($table, $where, $per_page, $page, $order, $order_method)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	/*
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_room_type()
	{
		$data = array(
				'room_type_name'=>ucwords(strtolower($this->input->post('room_type_name'))),
				'room_type_capacity'=>$this->input->post('room_type_capacity'),
				'room_type_status'=>1
			);
			
		if($this->db->insert('room_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_room_type($room_type_id)
	{
		$data = array(
				'room_type_name'=>ucwords(strtolower($this->input->post('room_type_name'))),
				'room_type_capacity'=>$this->input->post('room_type_capacity'),
				'room_type_status'=>1
			);
			
		$this->db->where(' room_type_id = '.$room_type_id);
		if($this->db->update('room_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}


	/*
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_room_type_charge()
	{
		$data = array(
				'room_type_id'=>$this->input->post('room_type_id'),
				'room_type_charge_amount'=>$this->input->post('room_type_charge_amount'),
				'room_type_charge_status'=>1
			);
			
		if($this->db->insert('room_type_charge', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_room_type_charge($room_type_id)
	{
		$data = array(
				'room_type_id'=>$this->input->post('room_type_id'),
				'room_type_charge_amount'=>$this->input->post('room_type_charge_amount'),
				'room_type_charge_status'=>1
			);
			
		$this->db->where(' room_type_charge_id = '.$room_type_id);
		if($this->db->update('room_type_charge', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function all_genders()
	{
		$this->db->order_by('gender_id');
		$query = $this->db->get('gender');
		
		return $query;
	}
	public function all_titles()
	{
		$this->db->order_by('title_id');
		$query = $this->db->get('title');
		
		return $query;
	}
	public function all_room_types()
	{
		$this->db->where('room_type_status = 1');
		$this->db->order_by('room_type_name');
		$query = $this->db->get('room_type');
		
		return $query;
	}
	public function building_floors($building_id)
	{
		$this->db->where('building.building_id = building_floor.building_id AND floor.floor_id = building_floor.floor_id AND building.building_id ='.$building_id);
		$this->db->order_by('building.building_id');
		$query = $this->db->get('building,building_floor,floor');
		
		return $query;
	}
	public function get_building_details($building_id)
	{
		$this->db->where('building.building_id = '.$building_id);
		$this->db->order_by('building.building_id');
		$query = $this->db->get('building');
		
		return $query;	
	}
	public function all_floors()
	{
		$this->db->order_by('floor_name');
		$query = $this->db->get('floor');
		
		return $query;
	}
	public function all_buildings()
	{
		$this->db->order_by('building_name');
		$query = $this->db->get('building');
		
		return $query;
	}



	/*
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_room()
	{
		$data = array(
				'room_type_id'=>$this->input->post('room_type_id'),
				'building_id'=>$this->input->post('building_id'),
				'floor_id'=>$this->input->post('floor_id'),
				'resident_key'=>$this->input->post('resident_key'),
				'master_key'=>$this->input->post('master_key'),
				'room_name'=>ucwords(strtolower($this->input->post('room_name'))),
				'room_status'=>1
			);
			
		if($this->db->insert('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_room($room_id)
	{
		$data = array(
				'room_type_id'=>$this->input->post('room_type_id'),
				'building_id'=>$this->input->post('building_id'),
				'floor_id'=>$this->input->post('floor_id'),
				'resident_key'=>$this->input->post('resident_key'),
				'master_key'=>$this->input->post('master_key'),
				'room_name'=>ucwords(strtolower($this->input->post('room_name'))),
				'room_status'=>1
			);
			
		$this->db->where(' room_id = '.$room_id);
		if($this->db->update('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function add_building()
	{
		$data = array(
				'building_name'=>ucwords(strtolower($this->input->post('building_name')))
			);
			
		if($this->db->insert('building', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_building_details($building_id)
	{

		$data = array(
				'building_name'=>ucwords(strtolower($this->input->post('building_name')))
			);
		$this->db->where('building_id = '.$building_id);
		if($this->db->update('building', $data))
		{
			$data = array(
				'floor_id'=>$this->input->post('floor_id'),
				'building_id'=>$building_id,
			);			
			if($this->db->insert('building_floor', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}


	/*
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_resident()
	{
		$data = array(
				'resident_othernames'=>ucwords(strtolower($this->input->post('resident_name'))),
				'resident_phone'=>$this->input->post('resident_phone'),
				'resident_email'=>$this->input->post('resident_email'),
				'gender_id'=>$this->input->post('gender_id'),
				'title_id'=>$this->input->post('title_id'),
				'resident_no'=>$this->create_resident_number(),
				'resident_status'=>0
			);
			
		if($this->db->insert('resident', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	public function create_resident_number()
	{

  		//select product code
		$this->db->from('resident');
		$this->db->select('MAX(resident_no) AS number');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$number++;//go to the next number
			if($number == 1){
				$number = "KMS/RES/000001";
			}
			
			if($number == 1)
			{
				$number = "KMS/RES/000001";
			}
			
		}
		else{//start generating receipt numbers
			$number = "KMS/RES/000001";
		}
		return $number;
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_resident($resident_id)
	{
		$data = array(
				'resident_othernames'=>ucwords(strtolower($this->input->post('resident_name'))),
				'resident_phone'=>$this->input->post('resident_phone'),
				'resident_email'=>$this->input->post('resident_email'),
				'gender_id'=>$this->input->post('gender_id'),
				'title_id'=>$this->input->post('title_id'),
				'resident_status'=>0
			);
			
		$this->db->where('resident_id = '.$resident_id);
		if($this->db->update('resident', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}



}
?>