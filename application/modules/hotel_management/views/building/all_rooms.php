<!-- search -->
<?php //echo $this->load->view('patients/search_patient', '', TRUE);?>
<!-- end search -->

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
        <div class="widget-icons pull-right" style="margin-top:-25px">
        	<?php
        	$search = $this->session->userdata('room_type_search');
		
			if(!empty($search))
			{
				echo '
				<a href="'.site_url().'close_resident_search" class="btn btn-warning btn-sm pull-right">Close Search</a>
				';
			}
			
			echo '
				<a href="'.site_url().'add-room" class="btn btn-success btn-sm pull-right" >Add Room</a>
				';
        	?>
        </div>
    </header>

        <!-- Widget content -->
        <div class="panel-body">
          <div class="padd">
		<?php
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		
		if(!empty($error))
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
				
		
		
		$result = '';
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Room Name</th>
						  <th>Room Type</th>
						  <th>Building</th>
						  <th>Floor</th>
						  <th>Availability</th>
						  <th>Capacity</th>
						  <th>Occupants</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			
			
			foreach ($query->result() as $row)
			{
				$room_type_id = $row->room_type_id;
				$room_type_name = $row->room_type_name;
				$room_type_capacity = $row->room_type_capacity;

				$building_name = $row->building_name;
				$floor_name = $row->floor_name;
				$room_name = $row->room_name;

				$room_status = $row->room_status;

				
				
				$count++;
			
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$room_name.' </td>
							<td>'.$room_type_name.' :: '.$room_type_capacity.' </td>
							<td>'.$building_name.' </td>
							<td>'.$floor_name.' </td>
							<td>'.$room_name.' </td>
							<td>'.$room_name.' </td>
							<td>'.$room_name.' </td>
							<td><a href="'.site_url().'room_type-details/'.$room_type_id.'" class="btn btn-sm btn-success">Edit</a></td>
							<td><a href="'.site_url().'delete-room_type/'.$room_type_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');">Delete</a></td>
						</tr> 
					';
				
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no patients";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </section>