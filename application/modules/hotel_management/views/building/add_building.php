 
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
                <div class="form-group center-align">
                        <label class="col-lg-2 col-sm-4 col-xs-4 control-label">Building</label>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                        	<input type="text" class="form-control" name="building_name" placeholder="Building Name" value="<?php echo set_value('room_type_name');?>" required>
                        </div>
                    </div>
                    
            </div>
            <br/>
             <br/>
              <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Building
                        </button>
                    </div>
            <?php echo form_close();?>
		</div>
    
</section>