<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	This module loads the head, header, footer &/or Social media sections.
*/
class Front_office extends MX_Controller  {
	
	/********************************************************************************************
	*																							*
	*			BY DEFAULT ALL ACTIONS WARANTY A CHECK TO SEE WHETHER THE USER'S		 		*
	*										SESSION IS ACTIVE									*
	*																							*
	********************************************************************************************/

	function __construct()
	{
		parent::__construct();
		
			$this->load->model('database');
			$this->load->model('front_office_model');
			$this->load->model('site/site_model');
			$this->load->model('admin/admin_model');
			$this->load->model('admin/sections_model');
			/*
				-----------------------------------------------------------------------------------------
				Load the requred class
				-----------------------------------------------------------------------------------------
			*/
			// $this->load->library('grocery_CRUD');
		
	}
	
	
	
	/********************************************************************************************
	*																							*
	*											RESIDENTS								 		*
	*																							*
	*																							*
	********************************************************************************************/
	
	public function residents()
    {
		

		$where = 'title.title_id = resident.title_id AND gender.gender_id = resident.gender_id AND resident_status = 0';
		
		$table = 'resident,gender,title';
		$resident_search = $this->session->userdata('resident_search');
		
		if(!empty($resident_search))
		{
			$where .= $resident_search;
		}
		
	
		$segment = 3;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'residents/resident-list';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_residents($table, $where, $config["per_page"],$page,$order = 'resident.resident_no', $order_method = 'DESC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'All Residents';
		$v_data['title'] = 'All Residents';
		
		
		$data['content'] = $this->load->view('residents/all_residents', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);

    }
	
	function resident_activation($primary_key)
	{
		$delete = array(
        		"resident_status" => 0
    		);
		$table = "resident";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->residents();
	}
	
	function remove_resident($primary_key)
	{
		$delete = array(
        		"scholarship_id" => 0
    		);
		$table = "resident";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$this->scholarship_residents($_SESSION['scholarship_id']);
	}
	
	public function resident_payments($primary_key)
    	{
		$_SESSION['payment_id'] = $primary_key;
		//get the financial year
		$financial_year_id = $this->get_financial_year();
		
		//get the table_id
  		$table = "table";
		$where = "table.table_name = 'resident'";
		$items = "table_id";
		$order = "table_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row)
			{
				$table_id = $row->table_id;
			}
		}
		
		else{
			$insert = array(
        		"table_name" => "resident"
    		);
			$table = "table";
			$this->load->model('database', '',TRUE);
			$this->database->insert_entry($table, $insert);
  		
			$table = "table";
			$where = "table.table_name = 'resident'";
			$items = "table_id";
			$order = "table_id";
		
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		
			if(count($result) > 0){
			
				foreach ($result as $row)
				{
					$table_id = $row->table_id;
				}
			}
		}
		
		$year = date("Y");
		$month = date("m");
		
		$crud = new grocery_CRUD();
 		
		$array = array('table_id' => $table_id, 'primary_key' => $primary_key, 'dc' => 'C');
 		$crud->where($array);
        $crud->set_subject('Payment');
        $crud->set_table('entry_items');
		$crud->columns('payment_type_id', 'amount', 'bankslip_number', 'bankslip_date', 'entry_items_year', 'entry_items_month', 'entry_items_timestamp');
		$crud->fields('payment_type_id', 'amount', 'bankslip_number', 'bankslip_date', 'entry_items_year', 'entry_items_month', 'financial_year_id', 'primary_key', 'table_id', 'entry_types_id', 'ledger_type_id', 'dc', 'units');
    	$crud->set_relation('payment_type_id', 'payment_type', 'payment_type_name');
		$crud->add_action('Print Receipt', base_url().'img/icons/icon-48-config.png', 'front_office/resident_receipt');
        $crud->display_as('bankslip_date','Bankslip Date');
        $crud->display_as('entry_items_year','Payment Year');
        $crud->display_as('entry_items_month','Payment Month');
        $crud->display_as('entry_items_timestamp','Date Added');
        $crud->display_as('payment_type_id','Payment Type');
		$crud->field_type('units', 'hidden', 1);
		$crud->field_type('dc', 'hidden', 'C');
		$crud->field_type('ledger_type_id', 'hidden', 15);
		$crud->field_type('entry_types_id', 'hidden', 1);
		$crud->field_type('table_id', 'hidden', $table_id);
		$crud->field_type('entry_items_year', 'hidden', $year);
		$crud->field_type('entry_items_month', 'hidden', $month);
		$crud->field_type('financial_year_id', 'hidden', $financial_year_id);
		$crud->field_type('primary_key', 'hidden', $primary_key);
		$crud->callback_after_insert(array($this,'update_bank'));
		$_SESSION['table'] = "resident";
		/*$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_add();*/
        
        $output = $crud->render();
 
        $this->_example_output3($output);
    }
	
	public function resident_room_details($value, $row)
	{
		//check if key has been assigned
		$table = "resident, room_resident, room, room_type, room_type_charge, building";
		$where = "building.building_id = room.building_id AND resident.resident_id = ".$row->resident_id." AND resident.resident_id = room_resident.resident_id AND room_resident.room_id = room.room_id AND room.room_type_id = room_type.room_type_id AND room_type.room_type_id = room_type_charge.room_type_id AND room_type_charge.room_type_charge_status = 0 AND room_resident.room_resident_status = 0";
		$items = "room.room_name, room_type.room_type_name, room_type_charge.room_type_charge_amount, building.building_name";
		$order = "room_name";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result)){
			foreach ($result as $row):
				$room = $row->room_name;
				$room_type = $row->room_type_name;
				$charge = $row->room_type_charge_amount;
				$building = $row->building_name;
			endforeach;
		}
		
		else{
			$room = "";
			$room_type = "";
			$charge = "";
			$building = "";
		}
		
		$value = $building." - ".$room." - ".$room_type." - ".$charge;
		
		return $value;
	}
	
	/*********************************************************************************************
	*
	*						RESIDENT BILL DATA MANIPULATION FUNCTIONS
	*
	*********************************************************************************************/
	public function register_resident_bill($resident_id, $current_page, $resident_receipt_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Bill Number
			--------------------------------------------------------------------------------------
		*/
		$result = $this->max_resident_bill_number();
		//echo "HERE";
		if(is_array($result)){//if next bill exists
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
				
				if($number == 1){
					$number = "KMS/B0001";
				}
			endforeach;
		}
					
		else{//start generating bill numbers
			$number = "KMS/B0001";
		}
		$date = date("Y-m-d");
		/*
			--------------------------------------------------------------------------------------
			save bill
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$insert = array(
			"resident_bill_number" => $number,
			"resident_bill_date" => $date,
			"personnel_id" => $this->session->userdata('personnel_id'),
			"resident_id" => $this->input->post("resident_id"),
			"financial_year_id" => $this->input->post("financial_year_id"),
			"resident_bill_year" => $this->input->post("resident_receipt_year"),
			"dc" => $this->input->post("dc"),
			"resident_bill_status" => 1
		);
		$resident_bill_id = $this->database->insert_entry($table, $insert);
		redirect("resident-details/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
	}	
	
	public function register_bill($resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Save resident receipt items
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill_item";
		$insert = array(
					"resident_bill_id" => $this->input->post("resident_bill_id"),
					"payment_type_id" => $this->input->post("payment_type_id"),
					"month_from" => $this->input->post("month_from"),
					"month_to" => $this->input->post("month_to"),
					"amount" => $this->input->post("amount")
		);
		$this->database->insert_entry($table, $insert);
		redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
		
		//$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
	}
	
	function max_resident_bill_number()
	{
		/*
			--------------------------------------------------------------------------------------
			Retrieve the highest resident bill number
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$items = "MAX(resident_bill_number) AS number";
		$order = "resident_bill_number";
		
		$result = $this->database->select_entries($table, $items, $order);
		
		return $result;
	}
	
	function delete_resident_bill($resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete from resident bill item
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill_item";
		$where = "resident_bill_id";
		$result = $this->database->delete_where($table, $resident_bill_id, $where);
		
		/*
			--------------------------------------------------------------------------------------
			Delete from resident bill
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$where = "resident_bill_id";
		$this->database->delete_where($table, $resident_bill_id, $where);
		
		echo 'true';
	}
	
	function delete_resident_bill_item($id, $resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete a bill item from the database
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill_item";
		
		$result = $this->database->delete($table, $id);
		
		redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
	}
	
	public function resident_bill($resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/resident_bill.php?payment_id=".$resident_bill_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		
		$this->update_resident_bill_status($resident_bill_id);
		$resident_bill_id = 0;
		$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
		//redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
	}
	
	public function resident_bill2($resident_bill_id, $resident_id)
	{
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/resident_bill.php?payment_id=".$resident_bill_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		
		$current_page = 0;
		$resident_receipt_id = 0;
		$resident_bill_id = 0;
		$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
	}
	
	public function resident_statement($resident_id)
	{
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/resident_statement.php?resident_id=".$resident_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		
		$current_page = 0;
		$resident_receipt_id = 0;
		$resident_bill_id = 0;
		$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
	}
	
	function update_resident_bill_status($resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Update resident bill status
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$items = array(
			"resident_bill_status" => 0
		);
		$this->database->update_entry($table, $items, $resident_bill_id);
	}
	
	/*********************************************************************************************
	*
	*						RESIDENT RECEIPT DATA MANIPULATION FUNCTIONS
	*
	*********************************************************************************************/
	public function register_resident_receipt($resident_id, $current_page, $resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Receipt Number
			--------------------------------------------------------------------------------------
		*/
		$result = $this->max_resident_receipt_number();
		//echo "HERE";
		if(is_array($result)){//if next receipt exists
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
				
				if($number == 1){
					$number = "KMS/R0001";
				}
			endforeach;
		}
					
		else{//start generating receipt numbers
			$number = "KMS/R0001";
		}
		
		$date = date("Y-m-d");
		/*
			--------------------------------------------------------------------------------------
			save receipt
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$insert = array(
			"resident_receipt_date" => $date,
			"resident_receipt_number" => $number,
			"personnel_id" => $this->session->userdata('personnel_id'),
			"resident_id" => $this->input->post("resident_id"),
			"financial_year_id" => $this->input->post("financial_year_id"),
			"bankslip_number" => $this->input->post("bankslip_number"),
			"bankslip_date" => $this->input->post("bankslip_date"),
			"resident_receipt_year" => $this->input->post("resident_receipt_year"),
			//"resident_receipt_payment" => $this->input->post("resident_receipt_payment"),
			"dc" => $this->input->post("dc"),
			"resident_receipt_status" => 1
		);
		$resident_receipt_id = $this->database->insert_entry($table, $insert);
		redirect("resident-details/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
		//$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
	}
	
	public function register_payment($resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Save resident receipt items
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt_item";
		$insert = array(
					"resident_receipt_id" => $this->input->post("resident_receipt_id"),
					"payment_type_id" => $this->input->post("payment_type_id"),
					"month_from_id" => $this->input->post("month_from_id"),
					"resident_receipt_item_payment" => $this->input->post("resident_receipt_item_payment")
		);
		$this->database->insert_entry($table, $insert);
		redirect("resident-details/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
		
		//$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
	}
	
	function delete_resident_receipt_item($id, $resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete a receipt item from the database
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt_item";
		
		$result = $this->database->delete($table, $id);
		
		redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
	}
	
	function update_resident_receipt_status($resident_receipt_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Update resident receipt status
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$items = array(
			"resident_receipt_status" => 0
		);
		$resident_receipt_id = $this->database->update_entry($table, $items, $resident_receipt_id);
	}
	
	public function resident_receipt($resident_id, $current_page, $resident_receipt_id, $resident_bill_id)
	{
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/resident_receipt2.php?payment_id=".$resident_receipt_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		$this->update_resident_receipt_status($resident_receipt_id);
		$resident_receipt_id = 0;
		$this->resident_details2($resident_id, $current_page, $resident_receipt_id, $resident_bill_id);
		//redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);
	}
	
	public function resident_receipt2($resident_receipt_id, $resident_id)
	{
		$lipsum = file_get_contents(base_url().'data/doc.pdf');
		
		$printer = printer_open("\\\\192.168.1.100\\HP Officejet Pro 8600 Class Driver (Copy 1)");
		
		printer_write($printer, $lipsum);
		
		printer_close($printer);
		?>
			<script type="text/javascript">
				//window.open("<?php echo base_url()."data/resident_receipt2.php?payment_id=".$resident_receipt_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		$current_page = 0;
		$resident_receipt_id = 0;
		$resident_bill_id = 0;
		$this->resident_details2($resident_id,$current_page,$resident_receipt_id,$resident_bill_id);
	}
	
	public function resident_receipt3($resident_receipt_id)
	{
		
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/resident_receipt2.php?payment_id=".$resident_receipt_id?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		$this->summaries($_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}
	
	function max_resident_receipt_number()
	{
		/*
			--------------------------------------------------------------------------------------
			Retrieve the highest resident receipt number
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$items = "MAX(resident_receipt_number) AS number";
		$order = "resident_receipt_number";
		
		$result = $this->database->select_entries($table, $items, $order);
		
		return $result;
	}
	
	/*********************************************************************************************
	*
	*										CONTROLLER FUNCTIONS
	*
	*********************************************************************************************/
	
	function bill_resident($primary_key)
	{
		/*
			--------------------------------------------------------------------------------------
			Check whether resident has been invoiced for that month
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$where = "month_id = ".date("m")." AND resident_id = ".$primary_key." AND resident_bill_year = '".date("Y")."'";
		$items = "*";
		$order = "resident_id";
			
		$bills = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($bills) == 0){
			/*
				--------------------------------------------------------------------------------------
				Bill Number
				--------------------------------------------------------------------------------------
			*/
			$result = $this->max_resident_bill_number();
			
			if(is_array($result)){//if next bill exists
				foreach ($result as $row):
					$number =  $row->number;
					$number++;//go to the next number
				
					if($number == 1){
						$number = "KMS/B0001";
					}
				endforeach;
			}
					
			else{//start generating bill numbers
				$number = "KMS/B0001";
			}
			
			$items = array(
						"month_id" => date("m"),
						"resident_id" => $primary_key,
						"resident_bill_number" => $number,
						"resident_bill_year" => date("Y"),
						"resident_bill_date" => date("Y-m-d"),
						"personnel_id" => 0,
						"dc" => "D",
						"financial_year_id" => $this->get_financial_year()
			);
			$resident_bill_id = $this->database->insert_entry($table, $items);
			
			/*
				--------------------------------------------------------------------------------------
				Select charge for that room
				--------------------------------------------------------------------------------------
			*/
			$table = "room_resident, room, room_type, room_type_charge";
			$where = "room_resident.room_resident_status = 0 AND 
						room_resident.resident_id = ".$primary_key." AND
						room_resident.room_id = room.room_id AND
						room.room_type_id = room_type.room_type_id AND
						room_type.room_type_id = room_type_charge.room_type_id AND
						room_type_charge.room_type_charge_status = 0";
			$items = "room_type_charge.room_type_charge_amount";
			$order = "room_type_charge_amount";
			
			$room_cost = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(is_array($room_cost)){
				foreach ($room_cost as $cost){
					$amount = $cost->room_type_charge_amount;
				}
			}
			else{
				$amount = 0;
			}
			
			/*
				--------------------------------------------------------------------------------------
				Bill resident for that room
				--------------------------------------------------------------------------------------
			*/
			if(!isset($amount)){
				$amount = 0;
			}
			$items = array(
						"resident_bill_id" => $resident_bill_id,
						"payment_type_id" => 1,
						"units" => 1,
						"amount" => $amount,
						"month_from" => date("m"),
						"month_to" => date("m")
			);
			$this->database->insert_entry("resident_bill_item", $items);
		}
		
		return TRUE;
	}
	
	public function resident_details($primary_key)
	{
		$this->bill_resident($primary_key);
		$this->resident_details2($primary_key, 1, 0, 0);
	}
	
	function sponsor_details($primary_key)
	{
		$data['scholarship_id'] = $primary_key;
		/*
			--------------------------------------------------------------------------------------
			retrieve sponsor
			--------------------------------------------------------------------------------------
		*/
		$table = "scholarship";
		$where = "scholarship_id = ".$primary_key;
		$items = "scholarship_name";
		$order = "scholarship_name";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		if(count($result) > 0){
			
			foreach($result as $it){
				$data['sponsor_name'] = $it->scholarship_name;
			}
		}
		
		/*
			--------------------------------------------------------------------------------------
			residents, room. building, room type, charges
			--------------------------------------------------------------------------------------
		*/
		$table = "resident";
		$where = "resident_status = 0 AND resident.scholarship_id <> ".$primary_key;
		$items = "resident.resident_no AS resident_number, resident.resident_othernames, resident.resident_id";
		$order = "resident_othernames";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$data['residents'] = "";
		
		if(count($result) > 0){
			
			foreach($result as $it){
				$resident_othernames = $it->resident_othernames;
				$resident_id = $it->resident_id;
				
				$data['residents'] .= "<option value=".$resident_id.">".$resident_othernames."</option>";
			}
		}
		
		/*
			--------------------------------------------------------------------------------------
			residents, room. building, room type, charges
			--------------------------------------------------------------------------------------
		*/
		$table = "resident, room_resident, room, room_type, room_type_charge, building";
		$where = "building.building_id = room.building_id AND resident.resident_id = room_resident.resident_id AND room_resident.room_id = room.room_id AND room.room_type_id = room_type.room_type_id AND room_type.room_type_id = room_type_charge.room_type_id  AND room_type_charge.room_type_charge_status = 0 AND room_resident.room_resident_status = 0 AND resident.scholarship_id = ".$primary_key;
		$items = "room.room_name, room_type.room_type_name, room_type_charge.room_type_charge_amount, building.building_name, resident.resident_no AS resident_number, resident.resident_othernames, resident.resident_id";
		$order = "resident_othernames";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$data['resident_details'] = "";
		
		if(count($result) > 0){
			
			$count = 0;
			$total = 0;
			
			foreach($result as $it){
				$room_name = $it->room_name;
				$resident_othernames = $it->resident_othernames;
				$room_type_name = $it->room_type_name;
				$room_type_charge_amount = $it->room_type_charge_amount;
				$building_name = $it->building_name;
				$resident_number = $it->resident_number;
				$resident_id = $it->resident_id;
				$count++;
				$total += $room_type_charge_amount;
				$data['resident_details'] .= 
				"
					<tr>
						<td>".$count."</td>
						<td>".$resident_number."</td>
						<td>".$resident_othernames."</td>
						<td>".$building_name."</td>
						<td>".$room_name."</td>
						<td>".$room_type_name."</td>
						<td>".$room_type_charge_amount."</td>
						<td><a href='".$resident_id."' class='btn btn-danger btn-sm delete_sponsorship'><i class='fa fa-trash-o'></i></a></td>
					</tr>
				";
			}
			
			$data['resident_details'] .= 
			"
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>".$total."</td>
					<td></td>
				</tr>
			";
		}
		
		/*
			--------------------------------------------------------------------------------------
			select the next payment number
			--------------------------------------------------------------------------------------
		*/
		$result = $this->max_payment_number();
		if($result != NULL){
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
				if($number == 1){
					$number = "KMS/SPN0001";
				}
				$payment_number = $number;
			endforeach;
		}
		
		else{//start generating payment numbers
			$payment_number = "KMS/SPN0001";
		}
		$data['payment_number'] = $payment_number;

		/*
			--------------------------------------------------------------------------------------
			Payment Types
			--------------------------------------------------------------------------------------
		*/
		$table = "payment_type";
		$where = "payment_type_id >= 0";
		$items = "*";
		$order = "payment_type_name";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$data['payment_type'] = "";
		
		if(count($result) > 0){
			
			foreach($result as $it){
				$payment_type_name = $it->payment_type_name;
				$payment_type_id = $it->payment_type_id;
				
				$data['payment_type'] .= "<option value=".$payment_type_id.">".$payment_type_name."</option>";
			}
		}
		
		/*
			--------------------------------------------------------------------------------------
			Retrieve Payments
			--------------------------------------------------------------------------------------
		*/
		$table = "payment, payment_type";
		$where = "payment.payment_type_id = payment_type.payment_type_id AND payment.scholarship_id = ".$primary_key;
		$items = "payment_amount, payment_number, payment_date, bankslip_number, payment_id";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$data['payment_details'] = "";
		
		if(count($result) > 0){
			
			$count = 0;
			$total = 0;
			
			foreach($result as $it){
				$payment_amount = $it->payment_amount;
				$payment_number = $it->payment_number;
				$payment_date = $it->payment_date;
				$bankslip_number = $it->bankslip_number;
				$payment_id = $it->payment_id;
				$count++;
				$total += $payment_amount;
				$data['payment_details'] .= 
				"
					<tr>
						<td>".$count."</td>
						<td>".$payment_date."</td>
						<td>".$payment_number."</td>
						<td>".$payment_amount."</td>
						<td>".$bankslip_number."</td>
						<td>
							<a href='".$payment_id."' class='btn btn-danger btn-sm delete_payment'>
								<i class='fa fa-trash-o'></i>
							</a>
							<a href='".site_url()."front_office/scholarship_print/".$payment_id."/".$primary_key."' class='btn btn-info btn-sm '>
								<i class='fa fa-print'></i>
							</a>
						</td>
					</tr>
				";
			}
			
			$data['payment_details'] .= 
			"
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>".$total."</td>
					<td></td>
				</tr>
			";
		}
		
		$this->head();
		$this->load->view('sponsor_details', $data);
		$this->footer();
	}
	
	function add_scholarship_residents($scholarship_id)
	{
		$this->form_validation->set_rules('resident_id', 'Resident', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->sponsor_details($scholarship_id);
		}
		
		else
		{
			$items = array(
						'scholarship_id' => $scholarship_id
					);
			
			$this->database->update_entry2("resident", "resident_id", $this->input->post("resident_id"), $items);
			redirect("front_office/sponsor_details/".$scholarship_id);
		}
	}
	
	function add_scholarship_payment($scholarship_id)
	{
		$this->form_validation->set_rules('payment_number', 'Payment Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('payment_type_id', 'Payment Type', 'trim|xss_clean');
		$this->form_validation->set_rules('payment_amount', 'Amount', 'trim|required|xss_clean');
		$this->form_validation->set_rules('bankslip_number', 'Bankslip Number', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->order_details($order_id);
		}
		
		else
		{
			$items = array(
						'scholarship_id' => $scholarship_id,
						'payment_number' => $this->input->post("payment_number"),
						'payment_type_id' => $this->input->post("payment_type_id"),
						'payment_amount' => $this->input->post("payment_amount"),
						'bankslip_number' => $this->input->post("bankslip_number")
					);
			$result = $this->database->insert("payment", $items);
			redirect("front_office/sponsor_details/".$scholarship_id);
		}
	}
	
	public function resident_details2($resident_id, $page, $resident_receipt_id, $resident_bill_id)
	{
			$primary_key = $resident_id;
			$data['resident_id2'] = $primary_key;
			$data['resident_receipt_id'] = $resident_receipt_id;
			$data['resident_bill_id'] = $resident_bill_id;
			//echo $resident_receipt_id;
			/*
				--------------------------------------------------------------------------------------
				Resident receipt details
				--------------------------------------------------------------------------------------
			*/
			if($resident_receipt_id > 0){
				$table = "resident_receipt";
				$where = "resident_receipt_id = ".$resident_receipt_id;
				$items = "*";
				$order = "resident_receipt_id";
			
				$data['resident_receipts'] = $this->database->select_entries_where($table, $where, $items, $order);
			}
			else{
				$data['resident_receipts'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Resident receipt items
				--------------------------------------------------------------------------------------
			*/
			if($resident_receipt_id > 0){
				$table = "resident_receipt_item, payment_type, `month`";
				$where = "`month`.month_id = resident_receipt_item.month_from_id 
				AND payment_type.payment_type_id = resident_receipt_item.payment_type_id 
				AND resident_receipt_item.resident_receipt_id = ".$resident_receipt_id;
				$items = "resident_receipt_item.resident_receipt_item_payment, payment_type.payment_type_name, resident_receipt_item.resident_receipt_item_id, month.month_name, resident_receipt_item.resident_receipt_id";
				$order = "resident_receipt_item.resident_receipt_id";
			
				$data['resident_receipt_items'] = $this->database->select_entries_where($table, $where, $items, $order);
			}
			else{
				$data['resident_receipt_items'] = NULL;
			}
			
			/*
				--------------------------------------------------------------------------------------
				Resident bill details
				--------------------------------------------------------------------------------------
			*/
			if($resident_bill_id > 0){
				$table = "resident_bill";
				$where = "resident_bill_id = ".$resident_bill_id;
				$items = "*";
				$order = "resident_bill_id";
			
				$data['resident_bills'] = $this->database->select_entries_where($table, $where, $items, $order);
			}
			else{
				$data['resident_bills'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Resident_bill items
				--------------------------------------------------------------------------------------
			*/
			if($resident_bill_id > 0){
				$table = "resident_bill_item, payment_type";
				$where = "payment_type.payment_type_id = resident_bill_item.payment_type_id AND 
			resident_bill_item.resident_bill_id = ".$resident_bill_id;
				$items = "resident_bill_item.amount, resident_bill_item.units, payment_type.payment_type_name, 
			resident_bill_item.resident_bill_item_id, month_to, month_from";
				$order = "resident_bill_item.resident_bill_item_id";
			
				$data['resident_bill_items'] = $this->database->select_entries_where($table, $where, $items, $order);
			}
			else{
				$data['resident_bill_items'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Residents
				--------------------------------------------------------------------------------------
			*/
			$table = "resident, gender, title";
			$where = "resident_id = ".$primary_key." AND resident.gender_id = gender.gender_id AND title.title_id = resident.title_id ";
			$items = "*";
			$order = "resident.resident_othernames";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			// var_dump($where);
			if(count($result) > 0){
				foreach ($result as $row)
				{
					$data['number'] = $row->resident_no;
					$data['othernames'] = $row->resident_othernames;
					$data['resident_gender'] = $row->gender_name;
					$data['resident_title'] = $row->title_name;
					$data['phone'] = $row->resident_phone;
					$data['email'] = $row->resident_email;
					$data['town'] = $row->resident_town;
					$data['resident_university'] = 'university_name';
					$data['course'] = $row->resident_course;
					$data['course_year'] = $row->resident_course_year;
					$data['doctor'] = $row->resident_doctor;
					$data['hospital'] = $row->resident_hospital;
					$data['hospital_location'] = $row->resident_hospital_location;
					$data['dob'] = $row->resident_dob;
				}
			}
			else{
				$data['resident_id'] = "";
				$data['number'] = "";
				$data['othernames'] = "";
				$data['resident_gender'] = "";
				$data['resident_title'] = "";
				$data['phone'] = "";
				$data['email'] = "";
				$data['town'] = "";
				$data['resident_university'] = "";
				$data['course'] = "";
				$data['course_year'] = "";
				$data['doctor'] = "";
				$data['hospital'] = "";
				$data['hospital_location'] = "";
				$data['dob'] = "";
			}

			/*
				--------------------------------------------------------------------------------------
				Titles
				--------------------------------------------------------------------------------------
			*/
			$table = "title";
			$where = "title_id >= 0";
			$items = "*";
			$order = "title_name";
			
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['title'] = $result;
			}
			else{
				$data['title'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Universities
				--------------------------------------------------------------------------------------
			*/
			$table = "university";
			$where = "university_id >= 0";
			$items = "*";
			$order = "university_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['university'] = $result;
			}
			else{
				$data['university'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Genders
				--------------------------------------------------------------------------------------
			*/
			$table = "gender";
			$where = "gender_id >= 0";
			$items = "*";
			$order = "gender_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['gender'] = $result;
			}
			else{
				$data['gender'] = NULL;
			}
			$data['height'] = 0;

			/*
				--------------------------------------------------------------------------------------
				room. building, room type, charges
				--------------------------------------------------------------------------------------
			*/
			$table = "resident, room_resident, room, room_type, room_type_charge, building";
			$where = "building.building_id = room.building_id AND resident.resident_id = ".$resident_id." AND resident.resident_id = room_resident.resident_id AND room_resident.room_id = room.room_id AND room.room_type_id = room_type.room_type_id AND room_type.room_type_id = room_type_charge.room_type_id  AND room_type_charge.room_type_charge_status = 0 AND room_resident.room_resident_status = 0";
			$items = "room.room_name, room_type.room_type_name, room_type_charge.room_type_charge_amount, building.building_name";
			$order = "room_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result)){
				foreach ($result as $row):
					$data['resident_room'] = $row->room_name;
					$data['resident_room_type'] = $row->room_type_name;
					$data['resident_charge'] = $row->room_type_charge_amount;
					$data['resident_building'] = $row->building_name;
				endforeach;
			}
			else{
				$data['resident_room'] = "";
				$data['resident_room_type'] = "";
				$data['resident_charge'] = "";
				$data['resident_building'] = "";
			}

			/*
				--------------------------------------------------------------------------------------
				rooms
				--------------------------------------------------------------------------------------
			*/
			$table = "room";
			$where = "room_id >= 0";
			$items = "DISTINCT(room_name)";
			$order = "room_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['room'] = $result;
			}
			else{
				$data['room'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				room types
				--------------------------------------------------------------------------------------
			*/
			$table = "room_type";
			$where = "room_type_id >= 0";
			$items = "*";
			$order = "room_type_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['room_type'] = $result;
			}
			else{
				$data['room_type'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Buildings
				--------------------------------------------------------------------------------------
			*/
			$table = "building";
			$where = "building_id >= 0";
			$items = "*";
			$order = "building_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			if(count($result) > 0){
				$data['building'] = $result;
			}
			else{
				$data['building'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Payment Types
				--------------------------------------------------------------------------------------
			*/
			$table = "payment_type";
			$where = "payment_type_id >= 0";
			$items = "*";
			$order = "payment_type_name";
			$result = $this->database->select_entries_where($table, $where, $items, $order);

			if(count($result) > 0){
				$data['payment_type'] = $result;
			}
			else{
				$data['payment_type'] = NULL;
			}

			/*
				--------------------------------------------------------------------------------------
				Resident Bills
				--------------------------------------------------------------------------------------
			*/
			$table = "resident_bill";
			$where = "resident_bill.resident_id = ".$primary_key;
			$items = "*";
			$order = "resident_bill_date";
			$result = $this->database->select_entries_where($table, $where, $items, $order);

			$payment_pages = $this->pagenate(count($result), $page, 10);
			$data['num_pages'] = $payment_pages["num_pages"];
			$data['current_page'] = $payment_pages["current_page"];
			$data['current_item'] = $payment_pages["current_item"];
			$data['next_page'] = $payment_pages["next_page"];	
			$data['previous_page'] = $payment_pages["previous_page"];
			$data['end_item'] = $payment_pages["end_item"];

			/*
				--------------------------------------------------------------------------------------
				Resident Bill Items
				--------------------------------------------------------------------------------------
			*/
			$table = "resident_bill, resident_bill_item, payment_type";
			$where = "payment_type.payment_type_id = resident_bill_item.payment_type_id AND 
			resident_bill.resident_bill_id = resident_bill_item.resident_bill_id AND resident_bill.resident_id = ".$primary_key;
			$items = "resident_bill_item.amount, resident_bill_item.units,payment_type.payment_type_name, resident_bill_item.resident_bill_item_id, resident_bill_item.resident_bill_id";
			$order = "resident_bill_item.resident_bill_id";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			$data['bill_items'] = $result;

			/*
				--------------------------------------------------------------------------------------
				Resident Invoices
				--------------------------------------------------------------------------------------
			*/
			$table = "resident_bill";
			$where = "resident_bill.resident_id = ".$primary_key;
			$items = "*";
			$order = "resident_bill_date";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			$data['past_resident_bills'] = $result;

			/*
				--------------------------------------------------------------------------------------
				Resident Receipts
				--------------------------------------------------------------------------------------
			*/
			$table = "resident_receipt";
			$where = "resident_receipt.resident_id = ".$primary_key;
			$items = "*";
			$order = "resident_receipt_date";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			$data['past_resident_receipts'] = $result;

			/*
				--------------------------------------------------------------------------------------
				Resident Receipt Items
				--------------------------------------------------------------------------------------
			*/
			$table = "resident_receipt_item, resident_receipt";
			$where = "resident_receipt_item.resident_receipt_id = resident_receipt.resident_receipt_id 
				AND resident_receipt.resident_id = ".$primary_key;
			$items = "resident_receipt_item.resident_receipt_id, resident_receipt_item.resident_receipt_item_payment";
			$order = "resident_receipt_item_payment";
			$result = $this->database->select_entries_where($table, $where, $items, $order);
			
			$data['receipt_items'] = $result;

			/*
				--------------------------------------------------------------------------------------
				Ledger Data
				--------------------------------------------------------------------------------------
			*/
			$data['table'] = "resident";
			$data['id'] = $primary_key;
			$data['financial_year_id'] = $this->get_financial_year();

			/*
				--------------------------------------------------------------------------------------
				Month
				--------------------------------------------------------------------------------------
			*/
			$table = "month";
			$where = "month_id >= 0";
			$items = "*";
			$order = "month_id";
			$result = $this->database->select_entries_where($table, $where, $items, $order);

			if(count($result) > 0){
				$data['month'] = $result;
			}
			else{
				$data['month'] = NULL;
			}
			$data['title'] ='Resident Details';
			
			$v_data['content'] = $this->load->view('residents/resident_details', $data, true);
			$v_data['title'] ='Resident Details';
			$this->load->view('admin/templates/general_page', $v_data);
	}
	
	function pagenate($total_items, $clicked_page, $items){
		
		$num_rows = $total_items;
		//$items = 20;
		$pages1 = intval($num_rows/$items);
		$pages2 = $num_rows%(2*$items);

		if($pages2 == NULL){//if there is no remainder
			$num_pages = $pages1;
		}

		else{
			$num_pages = $pages1 + 1;
		}
		
		$pagenation['num_pages'] = $num_pages;

		$current_page = $clicked_page;//if someone clicks a different page

		if($current_page < 1){//if different page is not clicked
			$current_page = 1;
		}

		else if($current_page > $num_pages){//if the next page clicked is more than the number of pages
			$current_page = $num_pages;
		}
		
		$pagenation['current_page'] = $current_page;
	
		if($current_page == 1){
			$current_item = 0;
		}

		else{
			$current_item = ($current_page-1) * $items;
		}
		
		$pagenation['current_item'] = $current_item;
		//echo "current = ".$current_page;
		$next_page = $current_page+1;
		//echo "next = ".$next_page;
		$pagenation['next_page'] = $next_page;

		$previous_page = $current_page-1;
		
		$pagenation['previous_page'] = $previous_page;

		$end_item = $current_item + $items;

		if($end_item > $num_rows){
			$end_item = $num_rows;
		}
		
		$pagenation['end_item'] = $end_item;
		
		return $pagenation;
	}
	
	function get_financial_year(){
		
		//get the financial year
  		$table = "financial_year";
		$where = "financial_year_status = 0";
		$items = "financial_year_id";
		$order = "financial_year_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row)
			{
				$financial_year_id = $row->financial_year_id;
			}
		}
		
		return $financial_year_id;
	}
	
	function delete_resident_receipt($receipt_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt item
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt_item";
		$where = "resident_receipt_id";
		$result = $this->database->delete_where($table, $receipt_id, $where);
		
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$where = "resident_receipt_id";
		$this->database->delete_where($table, $receipt_id, $where);
		
		redirect("front_office/summaries/".$_SESSION['navigation_id'].'/'.$_SESSION['sub_navigation_id']);
	}
	
	function delete_resident_receipt3($receipt_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt item
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt_item";
		$where = "resident_receipt_id";
		$result = $this->database->delete_where($table, $receipt_id, $where);
		
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$where = "resident_receipt_id";
		$this->database->delete_where($table, $receipt_id, $where);
		
		echo "true";
	}
	
	function delete_resident_receipt2($receipt_id, $resident_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt item
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt_item";
		$where = "resident_receipt_id";
		$result = $this->database->delete_where($table, $receipt_id, $where);
		
		/*
			--------------------------------------------------------------------------------------
			Delete from resident receipt
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_receipt";
		$where = "resident_receipt_id";
		$this->database->delete_where($table, $receipt_id, $where);
		
		redirect("front_office/resident_details2/".$resident_id."/0/0/0");
	}
	
	function delete_resident_bill2($bill_id, $resident_id)
	{
		/*
			--------------------------------------------------------------------------------------
			Delete from resident bill item
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill_item";
		$where = "resident_bill_id";
		$result = $this->database->delete_where($table, $bill_id, $where);
		
		/*
			--------------------------------------------------------------------------------------
			Delete from resident bill
			--------------------------------------------------------------------------------------
		*/
		$table = "resident_bill";
		$where = "resident_bill_id";
		$this->database->delete_where($table, $bill_id, $where);
		
		redirect("front_office/resident_details/".$resident_id);
	}
	
	public function update_resident_details($resident_id, $current_page)
	{
		$table = "resident";
		$where = "resident_id = ".$resident_id;
		$items = array(
					"resident_no" => $this->input->post("resident_no"),
					"resident_othernames" => $this->input->post("resident_othernames"),
					"gender_id" => $this->input->post("gender_id"),
					"title_id" => $this->input->post("title_id"),
					"resident_phone" => $this->input->post("resident_phone"),
					"resident_email" => $this->input->post("resident_email"),
					"resident_town" => $this->input->post("resident_town"),
					"resident_dob" => $this->input->post("resident_dob"),
					"university_id" => $this->input->post("university_id"),
					"resident_course" => $this->input->post("resident_course"),
					"resident_course_year" => $this->input->post("resident_course_year"),
					"resident_doctor" => $this->input->post("resident_doctor"),
					"resident_hospital" => $this->input->post("resident_hospital"),
					"resident_hospital_location" => $this->input->post("resident_hospital_location")
		);
		$this->database->update_entry4($table, $where, $items);  
		$no = 0;
		redirect("front_office/resident_details2/".$resident_id."/".$current_page."/".$no."/".$no);
	}
	
	public function update_resident_room_details($resident_id, $current_page)
	{
		$building = $this->input->post("building_id");
		$room = $this->input->post("room_name");
		// var_dump($room);die();
		//get room id
		$where = "building_id = ".$building." AND room_name = '".$room."'";
		// var_dump($where);die();
		$items = "room_id";
		$order = "room_id";
		$table = "room";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$room_id = $row2->room_id;
			}
			
			//check if resident is currently in that room
			$where = "room_resident_status = 0 AND resident_id = ".$resident_id." AND room_id = '".$room_id."'";
			$items = "room_id";
			$order = "room_id";
			$table = "room_resident";
		
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		
			if(count($result) > 0){//if resident is in room
				//leave them there
			}
			
			else{//if resident isnt in room
				//remove from room
				$table = "room_resident";
				$where = array(
        			"resident_id" => $resident_id,
        			"room_resident_status" => 0
    			);
				$items = array(
        			"room_resident_date_released" => date("Y-m-d"),
        			"room_resident_status" => 1
    			);
				$this->load->model('database', '', TRUE);
				$this->database->update_entry4($table, $where, $items);
				
				//assign a new room
				$insert = array(
        			"room_id" => $room_id,
        			"resident_id" => $resident_id
    			);
				$this->load->model('database', '',TRUE);
				$this->database->insert_entry($table, $insert);
			}
		} 
		$no = 0;
		redirect("resident-details/".$resident_id."/".$current_page."/".$no."/".$no);
	}

	function scholarships($navigation_id, $sub_navigation_id)
    {
		$_SESSION['sub_navigation_id'] = $sub_navigation_id;
		$crud = new grocery_CRUD();
		
		$crud->where("scholarship_status", 0);
        $crud->set_subject('Sponsor');
        $crud->set_table('scholarship');
		/*$crud->add_action('View Residents', base_url().'img/icons/icon-48-install.png', 'front_office/scholarship_residents');
		$crud->add_action('Payments', base_url().'img/icons/icon-48-config.png', 'front_office/scholarship_payments');*/
		$crud->add_action('Details', base_url().'img/icons/icon-48-upload.png', 'front_office/sponsor_details');
        $crud->columns('scholarship_name');
        $crud->fields('scholarship_name');
        $crud->display_as('scholarship_name','Sponsor');
        $crud->required_fields("scholarship_name");
		$_SESSION['table'] = "scholarship";
		$crud->callback_delete(array($this,'delete_log'));
		
        $output = $crud->render();
 		
		$this->_example_output3($output);
	}

	function scholarship_payments($scholarship_id)
	{
		//select the next payment number
		$result = $this->max_payment_number();
		if($result != NULL){
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
				if($number == 1){
					$number = "SPN0001";
				}
				$payment_number = $number;
			endforeach;
		}
		
		else{//start generating payment numbers
			$payment_number = "SPN0001";
		}
		$crud = new grocery_CRUD();
		
		$crud->where("scholarship_id", $scholarship_id);
        	$crud->set_subject('Payment');
        	$crud->set_table('payment');
        	$crud->columns('payment_date', 'payment_number', 'payment_type_id','bankslip_number', 'payment_amount', 'payment_units');
		$crud->add_action('Print', base_url().'img/icons/icon-48-print.png', 'front_office/scholarship_print');
    		//$crud->set_relation('scholarship_id', 'scholarship', 'scholarship_name');
    		$crud->set_relation('payment_type_id', 'payment_type', 'payment_type_name');
        	$crud->fields('payment_number', 'scholarship_id', 'payment_type_id', 'bankslip_number','payment_amount', 'payment_units');
		$crud->field_type('payment_number', 'hidden', $payment_number);
		$crud->field_type('scholarship_id', 'hidden', $scholarship_id);
        	$crud->display_as('payment_date','Date');
        	$crud->display_as('payment_number','Receipt Number');
        	$crud->display_as('payment_amount','Amount');
        	$crud->display_as('payment_units','Quantity');
        	$crud->display_as('scholarship_id','Sponsor');
        	$crud->display_as('payment_type_id','Payment Type');
        	$crud->required_fields('payment_amount', 'payment_units');
		$_SESSION['table'] = "payment";
		$_SESSION['scholarship_id'] = $scholarship_id;
		$crud->callback_delete(array($this,'delete_log'));
		$crud->callback_after_insert(array($this,'update_sponsored_residents'));
		
        	$output = $crud->render();
 		
		$this->_example_output3($output);
	}

	public function scholarship_print($primary_key, $scholarship_id)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url()."data/sponsorship_receipt.php?payment_id=".$primary_key?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        	<?php
		
		$this->sponsor_details($scholarship_id);
	}
	
	function asset_details($primary_key)
	{
		$crud = new grocery_CRUD();
 		$crud->where("resident_asset.resident_id", $primary_key);
        $crud->set_subject('Asset');
        $crud->set_table('resident_asset');
    	$crud->set_relation('asset_id', 'asset', 'asset_name');
    	$crud->set_relation('resident_id', 'resident', '{resident_othernames} {resident_surname}');
		$crud->required_fields('asset_id');
		//$crud->unset_fields("resident_asset_date_issued");
        $crud->display_as('resident_id','Resident');
		$crud->display_as('asset_id','Asset');
		$crud->display_as('resident_asset_date_issued','Date Issued');
        $crud->display_as('resident_asset_date_returned','Date Returned');
        $crud->display_as('resident_asset_remarks','Remarks');
        $crud->display_as('resident_asset_details','Details');
		$crud->field_type('resident_id', 'hidden', $primary_key);
        
        $output = $crud->render();
 
        $this->_example_output3($output);	
	}
	
	function guardian_details($primary_key)
	{
		$crud = new grocery_CRUD();
 		$crud->where("guardian.resident_id", $primary_key);
        $crud->set_subject('Guardian');
        $crud->set_table('guardian');
    	$crud->set_relation('guardian_type_id', 'guardian_type', 'guardian_type_name');
    	$crud->set_relation('resident_id', 'resident', '{resident_othernames} {resident_surname}');
    	$crud->set_relation('title_id', 'title', 'title_name');
    	$crud->set_relation('gender_id', 'gender', 'gender_name');
		$crud->required_fields('guardian_type_id','guardian_name');
        $crud->display_as('resident_id','Resident');
		$crud->display_as('guardian_names','Guardian Names');
		$crud->display_as('guardian_type_id','Guardian Type');
        $crud->display_as('guardian_phone','Phone');
        $crud->display_as('title_id','Title');
        $crud->display_as('gender_id','Gender');
		$crud->field_type('resident_id', 'hidden', $primary_key);
        
        $output = $crud->render();
 
        $this->_example_output3($output);	
	}

	public function building_details()
    {



        $where = 'building_id > 0';
		
		$table = 'building';
		$building_search = $this->session->userdata('building_search');
		
		if(!empty($building_search))
		{
			$where .= $building_search;
		}
		
	
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'residents/building';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_buildings($table, $where, $config["per_page"],$page,$order = 'building.building_name', $order_method = 'DESC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'All Buildings';
		$v_data['title'] = 'All Buildings';
		
		
		$data['content'] = $this->load->view('building/all_buildings', $v_data, true);
		
		
		
		$this->load->view('admin/templates/general_page', $data);
        
    }
	
	public function floors()
    {
		$where = 'floor_id > 0';
		
		$table = 'floor';
		$floor_search = $this->session->userdata('floor_search');
		
		if(!empty($floor_search))
		{
			$where .= $floor_search;
		}
		
	
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'residents/floors';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_floors($table, $where, $config["per_page"],$page,$order = 'floor.floor_name', $order_method = 'DESC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'All Buildings';
		$v_data['title'] = 'All Buildings';
		
		
		$data['content'] = $this->load->view('building/all_floors', $v_data, true);
		
		
		
		$this->load->view('admin/templates/general_page', $data);
    }
	
	public function room_type()
    { 
        
        $where = 'room_type_id > 0';
		
		$table = 'room_type';
		$room_search = $this->session->userdata('room_search');
		
		if(!empty($room_search))
		{
			$where .= $room_search;
		}
		
	
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'rooms/room-type';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_items($table, $where, $config["per_page"],$page,$order = 'room_type.room_type_name', $order_method = 'DESC');
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'All Rooms Type';
		$v_data['title'] = 'All Rooms Type';
		
		
		$data['content'] = $this->load->view('building/room_types', $v_data, true);
		
		
		
		$this->load->view('admin/templates/general_page', $data);
		
    }
	
	public function add_room_type()
	{
		//form validation rules
		$this->form_validation->set_rules('room_type_name', 'Room type Name', 'required|xss_clean');
		$this->form_validation->set_rules('room_type_capacity', 'Capacity', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->add_room_type())
			{
				$this->session->set_userdata('success_message', 'Room Type added successfully');
				redirect('room-types');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add room type. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Category';
		$v_data['title'] = 'Add New Category';
		$data['content'] = $this->load->view('building/add_room_type', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}

	public function add_room_charges()
	{
		//form validation rules
		$this->form_validation->set_rules('room_type_charge_amount', 'Room type charge amount', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('room_type_id', 'Room Type', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->add_room_type_charge())
			{
				$this->session->set_userdata('success_message', 'Room Type added successfully');
				redirect('room-charges');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add room type. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Charge';
		$v_data['title'] = 'Add New Charge';
		$v_data['all_room_types'] = $this->front_office_model->all_room_types();
		$data['content'] = $this->load->view('building/add_room_type_charge', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}

	public function add_room()
	{
		//form validation rules
		$this->form_validation->set_rules('room_name', 'Room Name', 'required|xss_clean');
		$this->form_validation->set_rules('room_type_id', 'Room Type', 'required|xss_clean');
		$this->form_validation->set_rules('building_id', 'Building', 'required|xss_clean');
		$this->form_validation->set_rules('floor_id', 'Floor Id', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->add_room())
			{
				$this->session->set_userdata('success_message', 'Room Type added successfully');
				redirect('rooms');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add room type. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Room';
		$v_data['title'] = 'Add New Room';
		$v_data['all_room_types'] = $this->front_office_model->all_room_types();
		$v_data['all_buildings'] = $this->front_office_model->all_buildings();
		$v_data['all_floors'] = $this->front_office_model->all_floors();
		$data['content'] = $this->load->view('building/add_room', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	public function add_resident()
	{
		//form validation rules
		$this->form_validation->set_rules('resident_name', 'Resident Name', 'required|xss_clean');
		$this->form_validation->set_rules('resident_email', 'Email Address', 'required|xss_clean');
		$this->form_validation->set_rules('resident_phone', 'Phone Number', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'gender', 'required|xss_clean');
		$this->form_validation->set_rules('title_id', 'title', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->add_resident())
			{
				$this->session->set_userdata('success_message', 'Resident added successfully');
				redirect('residents/resident-list');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not resident. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Resident';
		$v_data['title'] = 'Add New Resident';
		$v_data['all_genders'] = $this->front_office_model->all_genders();
		$v_data['all_titles'] = $this->front_office_model->all_titles();
		$data['content'] = $this->load->view('residents/add_resident', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	public function add_building()
	{
		//form validation rules
		$this->form_validation->set_rules('building_name', 'Building Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->add_building())
			{
				$this->session->set_userdata('success_message', 'Building added successfully');
				redirect('buildings');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not building. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Building';
		$v_data['title'] = 'Add New Building';
		$data['content'] = $this->load->view('building/add_building', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}

	public function edit_building_details($building_id)
	{
		//form validation rules
		$this->form_validation->set_rules('building_name', 'Building Name', 'required|xss_clean');
		$this->form_validation->set_rules('floor_id', 'Floor Id', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->front_office_model->update_building_details($building_id))
			{
				$this->session->set_userdata('success_message', 'Building floor added successfully');
				redirect('building-details/'.$building_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not building floor. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Building Floors';
		$v_data['title'] = 'Building Floors';
		$v_data['query'] = $this->front_office_model->get_building_details($building_id);
		$v_data['all_floors'] = $this->front_office_model->all_floors();
		$v_data['building_floors'] = $this->front_office_model->building_floors($building_id);

		$data['content'] = $this->load->view('building/edit_building', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	function max_payment_number()
	{
		$table = "payment";
		$items = "MAX(payment_number) AS number";
		$order = "payment_number";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table, $items, $order);
		
		return $result;
	}
	
	public function university($navigation_id, $sub_navigation_id)
    {
		$_SESSION['sub_navigation_id'] = $sub_navigation_id;
		$crud = new grocery_CRUD();
 
        $crud->set_subject('University');
        $crud->set_table('university');
		$crud->required_fields('university_name');
        $crud->display_as('university_name','University');
       
        $output = $crud->render();
 
        $this->_example_output3($output);
    }
	
	/********************************************************************************************
	*																							*
	*											ROOMS									 		*
	*																							*
	*																							*
	********************************************************************************************/
	
	public function room_details()
    {
        
       	$where = 'building.building_id = room.building_id AND room.floor_id = floor.floor_id AND room.room_type_id = room_type.room_type_id ';
		$table = 'room,floor,building,room_type';
		$room_type_charge_search = $this->session->userdata('room_type_charge_search');
		
		if(!empty($room_type_charge_search))
		{
			$where .= $room_type_charge_search;
		}
		
	
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'rooms/rooms';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_items($table, $where, $config["per_page"],$page,$order = 'room_type.room_type_name', $order_method = 'DESC');
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Rooms ';
		$v_data['title'] = 'Rooms';
		
		
		$data['content'] = $this->load->view('building/all_rooms', $v_data, true);
		
		
		
		$this->load->view('admin/templates/general_page', $data);
		
    }
	
	public function set_room_type($value, $row)
	{
  		$table = "room_type";
		$where = "room_type_id = ".$row->room_type_id;
		$items = "room_type_name, room_type_capacity";
		$order = "room_type_capacity";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$room_type_name = $row2->room_type_name;
				$room_type_capacity = $row2->room_type_capacity;
			}
		}

		$occupants = $this->occupants($value, $row);
		$capacity = $this->capacity($value, $row);

		if(($occupants == $capacity)){
			$room = "<div style='color:#B40404;'>FULL</div>";
		}
		else{
			$room = "<div style='color:#088A08;'>AVAILABLE</div>";
		}
		return $room;//." :: ".$occupants." :: ".$capacity;
	}
	
	public function occupants($value, $row)
	{
  		$table = "room_resident";
		$where = "room_resident.room_id = ".$row->room_id." AND room_resident.room_resident_status = 0";
		$items = "COUNT(resident_id) AS occupants";
		$order = "occupants";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row)
			{
				$occupants = $row->occupants;
			}
		}
		return $occupants;
	}
	
	public function capacity($value, $row)
	{
  		$table = "room_type";
		$where = "room_type_id = ".$row->room_type_id;
		$items = "room_type_capacity";
		$order = "room_type_capacity";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			
			foreach ($result as $row)
			{
				$room_type_capacity = $row->room_type_capacity;
			}
		}
		return $room_type_capacity;
	}
	
	public function room_residents($primary_key)
    {
		$crud = new grocery_CRUD();
 
 		$crud->where('room_resident.room_id', $primary_key);
        $crud->set_subject('Resident');
        $crud->set_table('room_resident');
		$crud->set_relation('room_id', 'room', '{room_name}');
		$crud->set_relation('resident_id', 'resident', '{resident_othernames} {resident_surname}');
		$crud->required_fields('resident_id');
		$crud->unset_columns('room_resident_priority');
		$crud->unset_fields('room_resident_date', 'room_resident_priority', 'room_resident_status', 'room_resident_date_released');
        $crud->display_as('room_resident_date','Date Added');
        $crud->display_as('room_resident_date_released','Date Released');
        $crud->display_as('resident_id','Resident');
        $crud->display_as('room_id','Room');
        $crud->display_as('room_resident_status','Status');
		$crud->callback_column('room_resident_status',array($this,'callback_status'));
		//$crud->add_action('Residents', base_url().'img/icons/icon-48-groups-add.png', 'front_office/room_details/'.$_SESSION['navigation_id'].'/'.$_SESSION['sub_navigation_id']);
		$crud->callback_insert(array($this,'check_room_capacity'));
		$crud->field_type('room_id', 'hidden', $primary_key);
		$crud->unset_edit();
        
        $output = $crud->render();
 
        $this->_example_output3($output);
    }
	
	public function callback_status($value, $row)
	{
  		if($value == 0){
			$value = "Current Status";
		}
		
		else{
			
			$value = "Previous Status";
		}
		
		return $value;
	}
	
	public function room_type_charge()
    {
		
        
       	$where = 'room_type_charge.room_type_id = room_type.room_type_id AND room_type_charge.room_type_charge_status = 1';
		
		$table = 'room_type_charge,room_type';
		$room_type_charge_search = $this->session->userdata('room_type_charge_search');
		
		if(!empty($room_type_charge_search))
		{
			$where .= $room_type_charge_search;
		}
		
	
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'rooms/room-type';
		$config['total_rows'] = $this->front_office_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->front_office_model->get_all_items($table, $where, $config["per_page"],$page,$order = 'room_type.room_type_name', $order_method = 'DESC');
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Room Type Charges';
		$v_data['title'] = 'Room Type Charges';
		
		
		$data['content'] = $this->load->view('building/room_type_charges', $v_data, true);
		
		
		
		$this->load->view('admin/templates/general_page', $data);
    }
	
	public function update_room_charge_status($post_array)
	{
		$room_type_id = $_POST['room_type_id'];
		$delete = array(
        	"room_type_charge_status" => 1
    	);
		$table = "room_type_charge";
		$key = $room_type_id;
		$this->load->model('database', '',TRUE);
		$this->database->update_room_type_charge($table, $delete, $key);
		
    	return $post_array;
	}
	
	/********************************************************************************************
	*																							*
	*											RECEIVABLES								 		*
	*																							*
	*																							*
	********************************************************************************************/

	function receivables($navigation_id, $sub_navigation_id)
    {
		$_SESSION['navigation_id'] = $navigation_id;
		$_SESSION['sub_navigation_id'] = 0;
		
		$crud = new grocery_CRUD();
		
		$crud->where("receivable_status", 0);
        $crud->set_subject('Receivable');
        $crud->set_table('receivable');
		$crud->add_action('Add Amount', base_url().'img/icons/icon-48-install.png', 'front_office/add_receivable_amount');
        $crud->columns('receivable_name');
        $crud->fields('receivable_name');
        $crud->display_as('receivable_name','Receivable');
        $crud->required_fields("receivable_name");
		$_SESSION['table'] = "receivable";
		$crud->callback_delete(array($this,'delete_log'));
		
        $output = $crud->render();
 		
		$this->_example_output3($output);
	}

	function deactivated_receivables($navigation_id, $sub_navigation_id)
    {
		$_SESSION['navigation_id'] = $navigation_id;
		$_SESSION['sub_navigation_id'] = $sub_navigation_id;
		
		$crud = new grocery_CRUD();
		
		$crud->where("receivable_status", 1);
        $crud->set_subject('Receivable');
        $crud->set_table('receivable');
        $crud->columns('receivable_name');
        $crud->display_as('receivable_name','Receivable');
        $crud->required_fields("receivable_name");
		$_SESSION['table'] = "receivable";
		$crud->unset_add();
		$crud->unset_edit();
		
        $output = $crud->render();
 		
		$this->_example_output3($output);
	}

	public function add_receivable_amount($primary_key)
	{
		$_SESSION['receivable_id'] = NULL;
		//select the next receivable amount number
		$result = $this->max_receivable_amount_number();
		if($result != NULL){
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
				if($number == 1){
					$number = "UT0001";
				}
				$receivable_amount_number = $number;
			endforeach;
		}
		
		else{//start generating receivable_amount numbers
			$receivable_amount_number = "UT0001";
		}
		$where = array(
        		"receivable_id" => $primary_key,
        		"receivable_amount_status" => 0
    		);
		$crud = new grocery_CRUD();
		
		$crud->where($where);
        $crud->set_subject('Receivable Amount');
        $crud->set_table('receivable_amount');
        $crud->columns('receivable_amount_date', 'receivable_amount_number','receivable_amount_name','receivable_amount_amount');
        $crud->fields('receivable_amount_name', 'receivable_amount_amount', 'receivable_amount_number', 'receivable_id');
		$crud->field_type('receivable_amount_number', 'hidden', $receivable_amount_number);
		$crud->field_type('receivable_id', 'hidden', $primary_key);
		$crud->add_action('Print', base_url().'img/icons/icon-48-print.png', 'front_office/print_receivable_amount');
        $crud->display_as('receivable_amount_date','Date');
        $crud->display_as('receivable_amount_name','Item');
        $crud->display_as('receivable_amount_amount','Amount');
        $crud->display_as('receivable_amount_number','Receipt Number');
        $crud->required_fields("receivable_amount_name","receivable_amount_amount");
		$_SESSION['receivable_id'] = $primary_key;
		$_SESSION['table'] = "receivable_amount";
		$crud->callback_delete(array($this,'delete_log'));
		
        $output = $crud->render();
 		
		$this->_example_output3($output);
	}

	public function print_receivable_amount($primary_key)
	{
		?>
        	<script type="text/javascript">
				window.open("<?php echo base_url()."data/receivable_receipt.php?receivable_amount_id=".$primary_key?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
			</script>
        	<?php
		
		$this->add_receivable_amount($_SESSION['receivable_id']);
	}
	
	function max_receivable_amount_number()
	{
		$table = "receivable_amount";
		$items = "MAX(receivable_amount_number) AS number";
		$order = "receivable_amount_number";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries($table, $items, $order);
		
		return $result;
	}
	
	/********************************************************************************************
	*																							*
	*											Logs									 		*
	*																							*
	*																							*
	********************************************************************************************/
	
	public function insert_log($post_array, $primary_key)
	{
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 1,
        		"personnel_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
    	return true;
	}
	
	public function update_log($post_array, $primary_key)
	{
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        	"log_key" => $primary_key,
        	"table_id" => $table_id,
        	"database_action_id" => 2,
        		"personnel_id" => $_SESSION['personnel_id']
    	);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
    	return true;
		$client_log_insert = array(
        	$_SESSION['table']."_table_id" => $primary_key,
        	"database_action_id" => 2,
        	"personnel_id" => $_SESSION['current_user']
    	);
	}
	
	public function delete_log($primary_key)
	{
		$delete = array(
        		$_SESSION['table']."_status" => 1
    		);
		$table = $_SESSION['table'];
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$table_id = $this->get_table_id($_SESSION['table']);
		$client_log_insert = array(
        		"log_key" => $primary_key,
        		"table_id" => $table_id,
        		"database_action_id" => 3,
        		"personnel_id" => $_SESSION['personnel_id']
    		);
		$table = "log";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $client_log_insert);
 
    	return true;
	}
	
	public function delete_log2($primary_key)
	{
		$delete = array(
        		"loan_scheme_status" => 1
    		);
		$table = "loan_scheme";
		$key = $primary_key;
		$this->load->model('database', '',TRUE);
		$this->database->update_entry($table, $delete, $key);
		
		$table_id = $this->get_table_id($_SESSION['table']);
 
    	return true;
	}
	
	public function get_table_id($table_name)
	{
		$table = "table";
		$where = "table_name = '$table_name'";
		$items = "table_id";
		$order = "table_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row):
				$table_id = $row->table_id;
			endforeach;
		}
		else{
			$items2 = array("table_name" => $table_name);
			$this->load->model('database', '', TRUE);
			$this->database->insert_entry($table, $items2);
		
			$this->load->model('database', '', TRUE);
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		
			foreach ($result as $row):
				$table_id = $row->table_id;
			endforeach;
		}
		return $table_id;
	}
	
	/********************************************************************************************
	*																							*
	*											SUMMARIES								 		*
	*																							*
	*																							*
	********************************************************************************************/
	
	public function get_invoice_status()
	{
		$table = "invoice_status";
		$where = "invoice_status_year = '".date("Y")."' AND month = '".date("m")."'";
		$items = "invoice_status_id";
		$order = "invoice_status_id";
		
		$this->load->model('database', '', TRUE);
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			$id = "Invoiced";
		}
		else{
			$id = "Uninvoiced";
		}
		return $id;
	}
	
	function print_summaries($page)
	{
		/*
			--------------------------------------------------------------------------------------
			Search
			--------------------------------------------------------------------------------------
		*/
		if($this->session->userdata('search_session') == TRUE){
			$_SESSION['date'] = $this->session->userdata('date');
			$_SESSION['building'] = $this->session->userdata('building');
			$_SESSION['room'] = $this->session->userdata('room');
			$_SESSION['resident'] = $this->session->userdata('resident');
			$_SESSION['summary_date'] = $this->session->userdata('summary_date');
			$_SESSION['payment_type'] = $this->session->userdata('payment_type');
		}
		else{
			$_SESSION['date'] = date("Y-m-01")."' and '".date("Y-m-31");
			$_SESSION['building'] = "";
			$_SESSION['room'] = "";
			$_SESSION['resident'] = "";
			$_SESSION['summary_date'] = "Summaries from ".date("Y-m-01")." to ".date("Y-m-31");
			$_SESSION['payment_type'] = "";
		}
		
		if($page == 1){
			$payment = "";
		}
		else{
			$table = "resident";
			$where = "resident_status = 0";
			$items = "resident_id";
			$order = "resident_id";
			$residents = $this->database->select_entries_where($table, $where, $items, $order);
			
			$resid = "(";
			$count = 0;
			
			foreach($residents as $res)
			{
				$count++;
				
				if($count == count($residents)){
					$resid .= $res->resident_id.")";
				}
				else{
					$resid .= $res->resident_id.", ";
				}
			}
			
			if($page == 3){
				$payment = "AND resident_receipt.resident_id IN ".$resid;
			}
			
			elseif($page == 2){
				$payment = "AND resident_receipt.resident_id NOT IN ".$resid;
			}
		}
		$_SESSION['payment'] = $payment;
		
		if($page == 1){
			
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/print_statement.php"?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		}
		
		elseif($page == 2){
			
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/print_unpaid_statement.php"?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		}
		
		elseif($page == 3){
			
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/print_paid_statement.php"?>","Popup","height=300,width=800,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		}
		$this->summaries($page, $_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}
	
	function summaries ($page, $navigation_id, $sub_navigation_id)
    {
		$_SESSION['navigation_id'] = $navigation_id;
		$_SESSION['sub_navigation_id'] = $sub_navigation_id;
		/*
			--------------------------------------------------------------------------------------
			Invoice unbilled residents
			--------------------------------------------------------------------------------------
		*/
		$invoice_status = $this->get_invoice_status();
		
		if($invoice_status == "Uninvoiced"){
			
			$table = "resident";
			$where = "resident_status = 0";
			$items = "resident_id";
			$order = "resident_id";
			$residents = $this->database->select_entries_where($table, $where, $items, $order);
			
			$insert = array(
        		"invoice_status_year" => date("Y"),
        		"month" => date("m")
    		);
			$table = "invoice_status";
			$this->database->insert_entry($table, $insert);
			
			foreach($residents as $res)
			{
				$id = $res->resident_id;
				$this->bill_resident($id);
			}
		}
		/*
			--------------------------------------------------------------------------------------
			Buildings
			--------------------------------------------------------------------------------------
		*/
			$table = "building";
			$where = "building_id > 0";
			$items = "*";
			$order = "building_name";
			$data2['buildings'] = $this->database->select_entries_where($table, $where, $items, $order);
		/*
			--------------------------------------------------------------------------------------
			Rooms
			--------------------------------------------------------------------------------------
		*/
			$table = "room";
			$where = "room_status = 0";
			$items = "*";
			$order = "room_name";
			$data2['rooms'] = $this->database->select_entries_where($table, $where, $items, $order);
		
		/*
			--------------------------------------------------------------------------------------
			Search
			--------------------------------------------------------------------------------------
		*/
		if($this->session->userdata('search_session') == TRUE){
			$date = $this->session->userdata('date');
			$building = $this->session->userdata('building');
			$room = $this->session->userdata('room');
			$resident = $this->session->userdata('resident');
			$payment_type = $this->session->userdata('payment_type');
			$data2['summary_date'] = $this->session->userdata('summary_date');
			$data2['search'] = 1;
		}
		else{
			$date = date("Y-m-01")."' and '".date("Y-m-31");
			$data2['summary_date'] = "Summaries from ".date("Y-m-01")." to ".date("Y-m-31");
			$building = "";
			$room = "";
			$resident = "";
			$payment_type = "";
		}
		
		if($page == 1){
			$payment = "";
		}
		else{
			$table = "resident";
			$where = "resident_status = 0";
			$items = "resident_id";
			$order = "resident_id";
			$residents = $this->database->select_entries_where($table, $where, $items, $order);
			
			$resid = "(";
			$count = 0;
			
			foreach($residents as $res)
			{
				$count++;
				
				if($count == count($residents)){
					$resid .= $res->resident_id.")";
				}
				else{
					$resid .= $res->resident_id.", ";
				}
			}
			
			if($page == 3){
				$payment = "AND resident_receipt.resident_id IN ".$resid;
			}
			
			elseif($page == 2){
				$payment = "AND resident_receipt.resident_id NOT IN ".$resid;
			}
		}
		
		/*
			--------------------------------------------------------------------------------------
			Select resident room & resident details
			--------------------------------------------------------------------------------------
		*/
		$table = "resident, room_resident, room, building";
		$where = "resident.resident_id = room_resident.resident_id
		AND room_resident.room_id = room.room_id
		AND room.building_id = building.building_id  
		AND room_resident.room_resident_status = 0
		AND room.room_status = 0
		AND resident.resident_status = 0 ".$resident." ".$building." ".$room;
		$items = "resident.resident_id, resident.resident_no, resident.resident_othernames, room.room_name, building.building_name";
		$order = "building_name, room_name";
		$data['resident_details'] = $this->database->select_entries_where($table, $where, $items, $order);
		
		/*
			--------------------------------------------------------------------------------------
			Select resident transactions
			--------------------------------------------------------------------------------------
		*/
  		$query = "SELECT resident_receipt_date, resident_receipt_id, resident_receipt_number, id, bankslip_number, resident_id 

					FROM `resident_receipt` 

					WHERE resident_receipt.resident_receipt_date BETWEEN '".$date."' ".$payment." 

					UNION SELECT resident_bill_date, resident_bill_id, resident_bill_number, id, resident_bill_date , resident_id
					FROM `resident_bill` 
					WHERE resident_bill.resident_bill_date 
					BETWEEN '".$date."'

					ORDER BY `resident_receipt_date` DESC";
					
		$data['transactions'] = $this->database->general_query($query);
		
		/*
			--------------------------------------------------------------------------------------
			Select resident receipts
			--------------------------------------------------------------------------------------
		*/
  		$table = "resident_receipt AS a, resident_receipt_item AS b";
		$where = "a.resident_receipt_date BETWEEN '".$date."' AND a.resident_receipt_id = b.resident_receipt_id ".$payment_type;
		$items = "b.resident_receipt_item_payment AS amount, b.resident_receipt_id";
		$order = "resident_receipt_id";
		$data['resident_receipt_items'] = $this->database->select_entries_where($table, $where, $items, $order);
		
		/*
			--------------------------------------------------------------------------------------
			Select resident bills
			--------------------------------------------------------------------------------------
		*/
  		$table = "resident_bill AS a, resident_bill_item AS b";
		$where = "a.resident_bill_date BETWEEN '".$date."' AND a.resident_bill_id = b.resident_bill_id ".$payment_type;
		$items = "b.amount, b.resident_bill_id";
		$order = "resident_bill_id";
		$data['resident_bill_items'] = $this->database->select_entries_where($table, $where, $items, $order);
		
		/*
			--------------------------------------------------------------------------------------
			Select payment_types
			--------------------------------------------------------------------------------------
		*/
  		$table = "payment_type";
		$where = "payment_type_id > 0";
		$items = "*";
		$order = "payment_type_name";
		$data2['payment_types'] = $this->database->select_entries_where($table, $where, $items, $order);
		
		$data['page'] = $page;
		$data2['page'] = $page;
		
		$this->head();
		$this->load->view('includes/front_office_head', $data2);
		
		if($page == 1){
			$this->load->view('summaries', $data);
		}
		
		elseif($page == 2){
			$this->load->view('unpaid_summaries', $data);
		}
		
		elseif($page == 3){
			$this->load->view('paid_summaries', $data);
		}
		
		$this->footer();
	}
	
	function search_summaries($page)
	{
		$from = $this->input->post("from");
		$to = $this->input->post("to");
		$building = $this->input->post("building_id");
		$room = $this->input->post("room_id");
		$resident = $this->input->post("resident");
		$payment_type = $this->input->post("payment_type_id");
		
		if((!empty($from)) && (!empty($to))){
			$date = $from."' and '".$to;
			$summary_date = "Summaries from ".$from." to ".$to;
		}
		else if((!empty($from)) && (empty($to))){
			$date = $from."' and '".$from;
			$summary_date = "Summaries for ".$from;
		}

		else if((empty($from)) && (!empty($to))){
			$date = $to."' and '".$to;
			$summary_date = "Summaries for ".$to;
		}
		else{
			$date = date("Y-m-01")."' and '".date("Y-m-31");
			$summary_date = "Summaries from ".date("Y-m-01")." to ".date("Y-m-31");
		}
		
		if($building == 0){
			$building = "";
		}
		else{
			$building = "AND building.building_id = ".$building;
		}
		
		if($room == 0){
			$room = "";
		}
		else{
			$room = "AND room.room_name = '".$room."'";
		}
		
		if(empty($resident)){
			$resident = "";
		}
		else{
			$resident = "AND resident.resident_othernames LIKE '%".$resident."%'";
		}
		
		if($payment_type == 0){
			$payment_type = "";
		}
		else{
			$payment_type = "AND payment_type_id = ".$payment_type."";
		}
		
		$newdata = array(
                   'date'  => $date,
                   'summary_date'  => $summary_date,
                   'building'  => $building,
                   'room'  => $room,
                   'resident'  => $resident,
                   'payment_type'  => $payment_type,
                   'search_session' => TRUE
               );

		$this->session->set_userdata($newdata);
		
		$this->summaries($page, $_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}
	
	function close_search($page)
	{
		if($this->session->userdata('search_session') == TRUE){
			$this->session->unset_userdata('search_session');
			$this->session->unset_userdata('search');
		}
		$this->summaries($page, $_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}
	
	function resident_number_fix()
	{
  		$table = "resident";
		$where = "resident_no = ''";
		$items = "resident_id";
		$order = "resident_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $res){
			
			$resident_id = $res->resident_id;
			
			$result = $this->max_resident_number();
			if($result != NULL){//if next receipt exists
				foreach ($result as $row):
					$number =  $row->number;
					$number++;//go to the next number
				endforeach;
				
				if($number == 1){
					$number = "KMS/RES/0001";
				}
			}
			else{//start generating receipt numbers
				$number = "KMS/RES/0001";
			}
			echo $resident_id." - ".$number;
		
			$table = "resident";
			$key = "resident_id";
			$key_value = $resident_id;
			$items = array(
				"resident_no" => $number
			);
			
			$this->database->update_entry2($table, $key, $key_value, $items);
		}
	}
	
	public function save_resident_number($post_array, $primary_key)
	{
		$result = $this->max_resident_number();
		if($result != NULL){//if next receipt exists
			foreach ($result as $row):
				$number =  $row->number;
				$number++;//go to the next number
			endforeach;
			
			if($number == 1){
				$number = "KMS/RES/0001";
			}
		}
		else{//start generating receipt numbers
			$number = "KMS/RES/0001";
		}
		
		$table = "resident";
		$key = "resident_id";
		$key_value = $primary_key;
		$items = array(
        	"resident_no" => $number
    	);
		
		$this->database->update_entry2($table, $key, $key_value, $items);
    	return true;
	}
	
	function max_resident_number()
	{
		$table = "resident";
		$items = "MAX(resident_no) AS number";
		$order = "resident_no";
		
		$result = $this->database->select_entries($table, $items, $order);
		
		return $result;
	}
}
