 
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
                        <label class="col-lg-6 control-label">Resident Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="resident_name" placeholder="Resident Name" value="<?php echo set_value('resident_name');?>" required>
                        </div>

                    </div>
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Resident Email</label>
                        <div class="col-lg-6">
                        	<input type="email" class="form-control" name="resident_email" placeholder="Resident Email" value="<?php echo set_value('resident_email');?>" required>
                        </div>
                        
                    </div>
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Resident Phone</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="resident_phone" placeholder="Resident Phone" value="<?php echo set_value('resident_phone');?>" required>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="col-md-6">
                   <div class="form-group">
                        <label class="col-lg-6 control-label">Gender</label>
                        <div class="col-lg-6">
                        	<select name="gender_id" class="form-control" required>
                            	<?php
        						echo '<option value="0">No Parent</option>';
        						if($all_genders->num_rows() > 0)
        						{
        							$result = $all_genders->result();
        							
        							foreach($result as $res)
        							{
        								if($res->gender_id == set_value('gender_id'))
        								{
        									echo '<option value="'.$res->gender_id.'" selected>'.$res->gender_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res->gender_id.'">'.$res->gender_name.'</option>';
        								}
        							}
        						}
        						?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Title</label>
                        <div class="col-lg-6">
                        	<select name="title_id" class="form-control" required>
                            	<?php
        						echo '<option value="0">Select a Title</option>';
        						if($all_titles->num_rows() > 0)
        						{
        							$result_titles = $all_titles->result();
        							
        							foreach($result_titles as $res_titles)
        							{
        								if($res_titles->title_id == set_value('title_id'))
        								{
        									echo '<option value="'.$res_titles->title_id.'" selected>'.$res_titles->title_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res_titles->title_id.'">'.$res_titles->title_name.'</option>';
        								}
        							}
        						}
        						?>
                            </select>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            <br>
                <div class="row">
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Resident 
                        </button>
                    </div>
                </div>
            <?php echo form_close();?>
		</div>
    
</section>