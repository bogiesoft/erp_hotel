 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right" >
                    <a href="<?php echo base_url();?>room-charges" class="btn btn-primary pull-right btn-sm">Back to Room Type Charges</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Amount</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="room_type_charge_amount" placeholder="Room Type Charge Amount" value="<?php echo set_value('room_type_charge_amount');?>" required>
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
                    
                </div>
                <br>
                <br>
                <div class="row">
                	<div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Room type Charge
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>