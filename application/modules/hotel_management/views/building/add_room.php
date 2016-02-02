 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right" >
                    <a href="<?php echo base_url();?>rooms" class="btn btn-primary pull-right btn-sm">Back to Rooms</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Room Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="room_name" placeholder="Room Name" value="<?php echo set_value('room_name');?>" required>
                        </div>

                    </div>
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Resident Key</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="resident_key" placeholder="Resident Key" value="<?php echo set_value('resident_key');?>" required>
                        </div>
                        
                    </div>
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Master Key</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="master_key" placeholder="master Key" value="<?php echo set_value('master_key');?>" required>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label class="col-lg-6 control-label">Room Type</label>
                        <div class="col-lg-6">
                        	<select name="room_type_id" class="form-control" required>
                            	<?php
        						echo '<option value="0">No Parent</option>';
        						if($all_room_types->num_rows() > 0)
        						{
        							$result = $all_room_types->result();
        							
        							foreach($result as $res)
        							{
        								if($res->room_type_id == set_value('room_type_id'))
        								{
        									echo '<option value="'.$res->room_type_id.'" selected>'.$res->room_type_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res->room_type_id.'">'.$res->room_type_name.'</option>';
        								}
        							}
        						}
        						?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Building</label>
                        <div class="col-lg-6">
                        	<select name="building_id" class="form-control" required>
                            	<?php
        						echo '<option value="0">Select Building</option>';
        						if($all_buildings->num_rows() > 0)
        						{
        							$result_building = $all_buildings->result();
        							
        							foreach($result_building as $res_building)
        							{
        								if($res_building->building_id == set_value('building_id'))
        								{
        									echo '<option value="'.$res_building->building_id.'" selected>'.$res_building->building_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res_building->building_id.'">'.$res_building->building_name.'</option>';
        								}
        							}
        						}
        						?>
                            </select>
                        </div>
                    </div>
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
                <br>
                <br>
                <div class="row">
                	<div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Room 
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>