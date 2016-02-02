<!-- search -->
<?php //echo $this->load->view('patients/search_patient', '', TRUE);?>
<!-- end search -->

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
        <div class="widget-icons pull-right " style="margin-top:-25px">
        	<?php
        	$search = $this->session->userdata('resident_search');
		
			if(!empty($search))
			{
				echo '
				<a href="'.site_url().'close_resident_search" class="btn btn-warning btn-sm pull-right">Close Search</a>
				';
			}
			
			echo '
				<a href="'.site_url().'add-resident" class="btn btn-success btn-sm pull-right" >Add resident</a>
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
						  <th>Resident No</th>
						  <th>Name</th>
						  <th>Phone</th>
						  <th colspan="5">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			
			
			foreach ($query->result() as $row)
			{
				$resident_id = $row->resident_id;
				$resident_no = $row->resident_no;
				$resident_surname = $row->resident_surname;
				$resident_othernames = $row->resident_othernames;
				$gender_id = $row->gender_id;
				$gender_name = $row->gender_name;
				$title_id = $row->title_id;
				$title_name = $row->title_name;
				$resident_phone = $row->resident_phone;
				$resident_email = $row->resident_email;
				$resident_town = $row->resident_town;

				
				
				$count++;
			
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$resident_no.' </td>
							<td>'.$resident_surname.' '.$resident_othernames.'</td>
							<td>'.$resident_phone.' </td>
							<td><a href="'.site_url().'resident-details/'.$resident_id.'" class="btn btn-sm btn-info"> <i class="fa fa-folder_open"></i>Open Details</a></td>
							<td><a href="'.site_url().'edit-resident/'.$resident_id.'" class="btn btn-sm btn-warning">Edit </a></td>
							<td><a href="'.site_url().'delete-resident/'.$resident_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');">Delete</a></td>
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