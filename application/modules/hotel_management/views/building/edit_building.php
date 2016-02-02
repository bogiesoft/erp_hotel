 <?php
	foreach ($query->result() as $row)
	{
		$building_id = $row->building_id;
		$building_name = $row->building_name;
	}
 ?>
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>buildings" class="btn btn-primary pull-right btn-sm">Back to Buildings</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
             <div class="row">
                <div class="col-lg-6">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Building Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="building_name" placeholder="Building Name" value="<?php echo $building_name;?>" required>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Floor</label>
                        <div class="col-lg-6">
                        	<select name="floor_id" class="form-control" required>
                            	<?php
        						echo '<option value="0">Floor</option>';
        						if($all_floors->num_rows() > 0)
        						{
        							$result_floors = $all_floors->result();
        							
        							foreach($result_floors as $res_floors)
        							{
        								if($res_floors->floor_id == set_value('floor_id'))
        								{
        									echo '<option value="'.$res_floors->floor_id.'" selected>'.$res_floors->floor_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res_floors->floor_id.'">'.$res_floors->floor_name.'</option>';
        								}
        							}
        						}
        						?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
             <br/>
              <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Edit Building
                        </button>
                    </div>
            <?php echo form_close();?>
            	<section class="panel">
		            <header class="panel-heading">
		              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
		              <div class="widget-icons pull-right">
		              </div>
		              <div class="clearfix"></div>
		        </header>
		        <div class="panel-body">
          
            	<?php
            		$result = '';
						//if users exist display them
						if ($building_floors->num_rows() > 0)
						{ 	
							$count = 0;
							
								$result .= 
								'
									<table class="table table-hover table-bordered ">
									  <thead>
										<tr>
										  <th>#</th>
										  <th>Floor Name</th>
										  <th colspan="2">Actions</th>
										</tr>
									  </thead>
									  <tbody>
								';
							
							
							
							foreach ($building_floors->result() as $row_floors)
							{
								$floor_id = $row_floors->floor_id;
								$floor_name = $row_floors->floor_name;

								
								
								$count++;
							
									$result .= 
									'
										<tr>
											<td>'.$count.'</td>
											<td>'.$floor_name.' </td>
											<td><a href="'.site_url().'building-details/'.$floor_id.'" class="btn btn-sm btn-success">Edit Details</a></td>
											<td><a href="'.site_url().'delete-building/'.$floor_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');">Delete</a></td>
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
							$result .= "There are no floors";
						}
						
						echo $result;
            	?>
            		</div>
            	</section>

		</div>

    
</section>
