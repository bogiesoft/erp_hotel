<!-- search -->
<?php //echo $this->load->view('patients/search_patient', '', TRUE);?>
<!-- end search -->

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
        <div class="widget-icons pull-right " style="margin-top:-25px">
        	<?php
        	$search = $this->session->userdata('building_search');
		
			if(!empty($search))
			{
				echo '
				<a href="'.site_url().'close_resident_search" class="btn btn-warning btn-sm pull-right">Close Search</a>
				';
			}
			
			echo '
				<a href="'.site_url().'add-building" class="btn btn-success btn-sm pull-right" >Add building</a>
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
						  <th>Building Name</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			
			
			foreach ($query->result() as $row)
			{
				$building_id = $row->building_id;
				$building_name = $row->building_name;

				
				
				$count++;
			
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$building_name.' </td>
							<td><a href="'.site_url().'building-details/'.$building_id.'" class="btn btn-sm btn-success">Edit Details</a></td>
							<td><a href="'.site_url().'delete-building/'.$building_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');">Delete</a></td>
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