<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Database extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	public function items_count($table, $where) {
        $this->db->where($where);
		$this->db->from($table);
        return $this->db->count_all_results();
    }
	
	function select_pagination($limit, $start, $table, $where, $items, $order)
	{
		$this->db->limit($limit, $start);
        
        $this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	function select_pagination2($limit, $start, $table, $where, $items, $order)
	{
		$this->db->limit($limit, $start);
        
        $this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "desc"); 
		
		$query = $this->db->get();
 
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	/*
		-----------------------------------------------------------------------------------------
		Save data to the database
		-----------------------------------------------------------------------------------------
	*/
	 function insert($table, $items)
    {
        $this->db->insert($table, $items);
		
		return $this->db->insert_id();
    }
	 function select($table)
    {
        $query = $this->db->get($table);
		return $query->result();
    }
	
	function update_resident_assignment($table, $items, $key)
	{
		$this->db->where("resident_id", $key);
        $this->db->update($table, $items);
	}
	
	function update_staff_assignment($table, $items, $key)
	{
		$this->db->where("personnel_id", $key);
        $this->db->update($table, $items);
	}
	
	 function insert_entry_form($table)
    {
        $items = $this->input->post(NULL, TRUE);
        $this->db->insert($table, $items);
    }
	
	 function update_entry($table, $items, $key)
    {
		$this->db->where($table."_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_entry2($table, $key, $key_value, $items)
    {
		$this->db->where($key, $key_value);
        $this->db->update($table, $items);
    }
	
	 function update_entry3($table, $items)
    {
        $this->db->update($table, $items);
    }
	
	 function update_entry4($table, $where, $items)
    {
		$this->db->where($where);
        $this->db->update($table, $items);
    }
	
	 function update_entry5($table, $key, $resident_id)
    {
        $items = $this->input->post(NULL, TRUE);
		$this->db->where($key, $resident_id);
        $this->db->update($table, $items);
    }
	
	function insert_entry2($table)
	{
		$item=$this->input->post(NULL, TRUE);
		$this->db->insert($table, $item);
	}
	
	function delete($table, $id)
	{
		$this->db->delete($table, array($table.'_id' => $id)); 
	}
	
	function delete2($table, $id)
	{
		$this->db->delete($table, array('id' => $id)); 
	}
	
	function delete_where($table, $id, $where)
	{
		$this->db->delete($table, array($where => $id)); 
	}
	
	function delete_where2($table, $array)
	{
		$this->db->delete($table, $array); 
	}
	
	 function insert_entry($table, $items)
    {
        $this->db->insert($table, $items);
		return $this->db->insert_id();
    }
	
	 function insert_sponsorship_payment($receipt_number, $resident_id, $table_id, $financial_year_id, $dc, $units, $payment_type_id, $bankslip_number, $month, $amount)
    {
		
		$items = array(
   			'receipt_number' => $receipt_number,
   			'primary_key' => $resident_id,
   			'table_id' => $table_id,
   			'financial_year_id' => $financial_year_id,
   			'dc' => $dc,
   			'units' => $units,
   			'payment_type_id' => $payment_type_id,
   			'amount' => $amount,
   			'bankslip_number' => $bankslip_number,
   			'bankslip_date' => date("Y-m-d"),
   			'entry_items_year' => date("Y"),
   			'entry_items_month' => $month
		);
		
        $this->db->insert($table, $items);
    }
	
	 function select_entries($table, $items, $order)
    {
		$this->db->select($items);
		$this->db->from($table);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		
		return $query->result();
    }
	
	 function select_entries_order($table, $order)
    {
        $this->db->select('*');
		$this->db->from($table);
		$this->db->order_by($order, "asc"); 
		$query = $this->db->get();
		
		return $query->result();
    }
	
	 function update_room_type_charge($table, $items, $key)
    {
		$this->db->where("room_type_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_basic_pay($table, $items, $key)
    {
		$this->db->where("personnel_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_room_resident($table, $items, $key)
    {
		$this->db->where("resident_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_personnel_allowance($table, $items, $key)
    {
		$this->db->where("allowance_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_personnel_deduction($table, $items, $key)
    {
		$this->db->where("deduction_id", $key);
        $this->db->update($table, $items);
    }
	
	 function select_entries_where($table, $where, $items, $order)
    {
		$this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		
		return $query->result();
    }
	
	function general_query($query)
	{
		$query = $this->db->query($query);
		return $query->result();
	}
	
	 function select_entries_where2($table, $where, $items, $order)
    {
		$this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "DESC"); 
		
		$query = $this->db->get();
		
		return $query->result();
    }
	
	 function select_entries_where_limit($table, $where, $items, $order, $upper_limit, $lower_limit)
    {
		$this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "DESC"); 
		$this->db->limit($lower_limit, $upper_limit);
		
		$query = $this->db->get();
		
		return $query->result();
    }
}

?>
