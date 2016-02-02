 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>room-types" class="btn btn-primary pull-right btn-sm">Back to Room Types</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Room Type Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="room_type_name" placeholder="Room Type Name" value="<?php echo set_value('room_type_name');?>" required>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label class="col-lg-6 control-label">Room Type Capacity</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="room_type_capacity" placeholder="Room type capacity" value="<?php echo set_value('room_type_capacity');?>" required>
                        </div>
                    </div>
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Room Type
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>