
<section class="panel">
<div class="row">
    <div class="col-md-12">

	      <!-- Widget -->
	     <header class="panel-heading">
	          <h4 class="pull-left"><i class="icon-reorder"></i>Resident: <?php echo $othernames;?></h4>
	          <div class="widget-icons pull-right">
	                 <a href="<?php echo site_url();?>residents/resident-list" class="btn btn-success btn-sm pull-right">  Resident List</a>
	          </div>
	          <div class="clearfix"></div>
	    </header>             

	        <!-- Widget content -->
	    <div class="panel-body">
	          <div class="padd">
	          <div class="center-align">
	          	<section class="panel">
					<div class="row">
					    <div class="col-md-12">

						      <!-- Widget -->
						     <header class="panel-heading">
						          <h4 class="pull-left"><i class="icon-reorder"></i>Room Details</h4>
						          <div class="widget-icons pull-right">
						          </div>
						          <div class="clearfix"></div>
						    </header>             

						        <!-- Widget content -->
						    <div class="panel-body">
						          <div class="padd">
						          <div class="center-align">
						          	<form action="<?php echo site_url('update-resident-room-details/'.$resident_id2.'/1')?>" method="post" class="niceform">
										<fieldset>
						        			<div class="row">
						        				<div class="col-sm-4 col-md-4 sub_section_border">
													<div class='sub_section_title'>Building Information</div>
													<dl>
														<dt><label>Building: </label></dt>
														<dd>
															<select size="1" name="building_id" class="form-control" >
																<?php
																	if(count($building) > 0){
																		foreach ($building as $row):
																			$building_name = $row->building_name;
																			$building_id = $row->building_id;
																			if($resident_building == $building_name){
																				echo "<option selected='selected' value='".$building_id."'>".$building_name."</option>";
																			}
																			else{
																				echo "<option value='".$building_id."'>".$building_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                        </div><!-- Building Information -->
						                        
						        				<div class="col-sm-4 col-md-4 sub_section_border">
													<div class='sub_section_title'>Room Information</div>
													<dl>
														<dt><label>Room: </label></dt>
														<dd>
															<select size="1" name="room_name" class="form-control" >
																<?php
																	if(count($room) > 0){
																		foreach ($room as $row):
																			$room_name = $row->room_name;
																			$room_id = $row->room_id;
																			if($resident_room == $room_name){
																				echo "<option selected='selected' value=".$room_name.">".$room_name."</option>";
																			}
																			else{
																				echo "<option value=".$room_name.">".$room_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                        </div><!-- End Room Information -->
						                        
						        				<div class="col-sm-4 col-md-4 ">
													<div class='sub_section_title'>Room Type Information</div>
													<dl>
														<dt><label>Room Type: </label></dt>
														<dd>
															<select size="1" name="room_type_id" class="form-control" >
																<?php
																	if(count($room_type) > 0){
																		foreach ($room_type as $row):
																			$room_type_name = $row->room_type_name;
																			$room_type_id = $row->room_type_id;
																			if($resident_room_type == $room_type_name){
																				echo "<option selected='selected' value='".$room_type_id."'>".$room_type_name."</option>";
																			}
																			else{
																				echo "<option value='".$room_type_id."'>".$room_type_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                        </div><!-- End Room Type Information -->
						                    </div><!-- End row -->
											
						        			<div class="row">
						        				<div class="col-sm-2 col-md-2 col-md-offset-5">
						                    			<input type="submit" name="submit" id="submit" value="Update" class="btn btn-success" />
						                        </div>
						                    </div><!-- End Row -->
										</fieldset><!-- End Fieldset -->
									</form><!-- End Form -->

						          </div>
							     </div>
							    </div>
							  </div>
						  </div>
						</section>
					<section class="panel">
					<div class="row">
					    <div class="col-md-12">

						      <!-- Widget -->
						     <header class="panel-heading">
						          <h4 class="pull-left"><i class="icon-reorder"></i>Receipts</h4>
						          <div class="widget-icons pull-right">
						          </div>
						          <div class="clearfix"></div>
						    </header>             

						        <!-- Widget content -->
						    <div class="panel-body">
						          <div class="padd">
						          <div class="center-align">
						         		<div class="row">
							        		<div class="col-sm-6 col-md-6 sub_section_border">
												<div class='sub_section_title'>New Receipt</div>       
												<form action="<?php echo site_url("register-resident-receipt/".$resident_id2."/".$current_page."/".$resident_bill_id)?>" method="post" class="niceform"> 
							            			<input type="hidden" name="resident_id" value="<?php echo $resident_id2?>"/>
							            			<input type="hidden" name="financial_year_id" value="<?php echo $financial_year_id?>"/>
													<fieldset>
									        			<div class="row">
							        						<div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Payment Year: </label></dt>
																	<dd><input type="text" name="resident_receipt_year"  class="form-control" value="<?php echo date("Y");?>" class="form-control" /></dd>
																</dl>
							                		        </div>
							        						<div class="col-sm-6 col-md-6">
																
							        		             		<dl>
							                                    	Debit Note:
							                		    			<select name="dc" class="form-control">
							                                        	<option value="C">No</option>
							                                        	<option value="D">Yes</option>
							                                        </select>
							                    				</dl>
									                        </div>
							        		            </div>
									        			<div class="row">
							        						<div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Bankslip Number: </label></dt>
																	<dd><input type="text" name="bankslip_number"  class="form-control" value="" class="form-control" /></dd>
																</dl>
							        		                </div>
							        						<div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Bankslip Date: </label></dt>
																	<dd><input type="text" name="bankslip_date" id="datepicker"  class="form-control" value="<?php echo date("Y-m-d");?>" /></dd>
																</dl>
							        		                </div>
							                            </div>
									        			<!--<div class="row-fluid">
									        			                <div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Amount: </label></dt>
																	<dd><input type="text" name="resident_receipt_payment"  class="form-control" value="" /></dd>
																</dl>
									                        </div>
							                            </div>-->
							        					
							        					<div class="row">
							        						<div class="col-sm-2 col-md-2 col-md-offset-5">
							                		    			<input type="submit" value="Create Receipt" class="btn btn-success"  />
									                        </div>
							        		            </div>
													</fieldset>
												</form>
							          		</div>
									        			                <div class="col-sm-6 col-md-6">
											<?php 
												if(count($resident_receipts) > 0){
													foreach($resident_receipts as $row3):
														$resident_receipt_number = $row3->resident_receipt_number;
														$bankslip_number = $row3->bankslip_number;
														$amount = $row3->resident_receipt_payment;
																	
														$rec = "<strong>Receipt No. </strong>".$resident_receipt_number."
														
														<strong> Bankslip No.</strong>".$bankslip_number;
													endforeach;
												}
												else{
													$rec = "";
												}
											?>
							                	<div class='sub_section_title'><?php echo $rec;?></div>
												<?php if($resident_receipt_id > 0): ?>       
												<form action="<?php echo site_url("register-payment/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id)?>" method="post"> 
							                                   
							            			<input type="hidden" name="resident_receipt_id" value="<?php echo $resident_receipt_id?>"/>
													<fieldset>
									        			<div class="row">
							        						<div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Payment Type: </label></dt>
																	<dd>
																		<select size="1" name="payment_type_id" class="form-control">
																			<?php
																				if(count($payment_type) > 0){
																					foreach ($payment_type as $row):
																						$payment_type_name = $row->payment_type_name;
																						$payment_type_id = $row->payment_type_id;
																						if($resident_payment_type == $payment_type_name){
																							echo "<option selected='selected' value='".$payment_type_id."'>".$payment_type_name."</option>";
																						}
																						else{
																							echo "<option value='".$payment_type_id."'>".$payment_type_name."</option>";
																						}
																					endforeach;
																				}
																			?>
																		</select>
																	</dd>
																</dl>
							                		        </div>
							                                <div class="col-sm-6 col-md-6">
																<dl>
																	<dt><label>Amount: </label></dt>
																	<dd><input type="text" name="resident_receipt_item_payment"  class="form-control" value="" /></dd>
																</dl>
									                        </div>
							                            </div>
									        			<div class="row">
							        						<div class="col-sm-6 col-md-6">
							        		             		<dl>
																	<dt><label>Month from: </label></dt>
																	<dd>
																		<select size="1" name="month_from_id" class="form-control">
																			<?php
																				if(count($month) > 0){
																					foreach ($month as $row):
																						$month_name = $row->month_name;
																						$month_id = $row->month_id;
							//															echo "month ".date("M");
																						if(date("M") == $month_name){
																							echo "<option selected='selected' value='".$month_id."'>".$month_name."</option>";
																						}
																						else{
																							echo "<option value='".$month_id."'>".$month_name."</option>";
																						}
																					endforeach;
																				}
																			?>
																		</select>
																	</dd>
																</dl>
									                        </div>
							        						<!--<div class="span6">
																<dl>
																	<dt><label>Month to: </label></dt>
																	<dd>
																		<select size="1" name="month_to_id">
																			<?php
																				if(count($month) > 0){
																					foreach ($month as $row):
																						$month_name = $row->month_name;
																						$month_id = $row->month_id;
							//															echo "month ".date("M");
																						if(date("M") == $month_name){
																							echo "<option selected='selected' value='".$month_id."'>".$month_name."</option>";
																						}
																						else{
																							echo "<option value='".$month_id."'>".$month_name."</option>";
																						}
																					endforeach;
																				}
																			?>
																		</select>
																	</dd>
																</dl>
									                        </div>-->
							        		            </div>
							        					<div class="row">
							        						<div class="col-sm-2 col-md-2 col-md-offset-5">
							                		    			<input type="submit" value="Add Payment" class="btn btn-success"  />
									                        </div>
							        		            </div>
							        					<div class="row">
									        				<div class="col-sm-9 col-md-9">
							        		             		<table class="table table-condensed table-striped table-hover">
																	<thead>
							    										<tr>
							        										<th scope="col" class="rounded-company">Delete</th>
							            									<th scope="col" class="rounded">#</th>
							            									<th scope="col" class="rounded">Payment</th>
							            									<th scope="col" class="rounded">Month</th>
							            									<th scope="col" class="rounded-q4">Amount</th>
							        									</tr>
							    									</thead>
							    									<tbody>
							            							<?php
																	
																	if(is_array($resident_receipt_items))
																	{
																		$count = 0;
																		$total_amount = 0;
																		
																		foreach($resident_receipt_items as $row3):
																			$count++;
																			$receipt_item_amount = $row3->resident_receipt_item_payment;
																			$total_amount += $receipt_item_amount;
																			$payment = $row3->payment_type_name;
																			$id = $row3->resident_receipt_item_id;
																			$month = $row3->month_name;
																			
																			if($payment != "Accomodation"){
																				$month = "";
																			}
																	
																			echo"
																				<tr>
																					<td>
																						<a href='".site_url("/front_office/delete_resident_receipt_item/".$id."/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id)."'<div class='btn-group'>
																							<img src='".base_url()."img/delete.png' alt='' title='' border='0' />
																						</div></a>
																					</td>
																					<td>".$count."</td>
																					<td>".$payment."</td>
																					<td>".$month."</td>
																					<td>".$receipt_item_amount."</td>
																				</tr>
																			";
																		endforeach;
																	}
																	?>
							    										<tr>
							        										<td colspan="4">Receipt Total</td>
							            									<td><?php echo $total_amount;?></td>
							        									</tr>
							    										<!--<tr>
							        										<td colspan="4">Payment Total</td>
							            									<td><?php echo $amount;?></td>
							        									</tr>
							    										<tr>
							        										<td colspan="4">Balance</td>
							            									<td><?php echo ($amount - $total_amount);?></td>
							        									</tr>-->
																</table>
									                        </div>
							        		            </div>
							                            
							        					<div class="row">
							        						<div class="col-sm-2 col-md-2 col-md-offset-5">
							                                	<a href="<?php echo site_url("/front_office/resident_receipt/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);?>">
																		<input type="button" value="Print" class="btn btn-info" />
																	</a>
									                        </div>
							        		            </div>
							                            
													</fieldset>
												</form>
												<?php endif; ?>
							         		</div>
							            </div>
						          </div>
							     </div>
							    </div>
							  </div>
						  </div>
						</section>
					<section class="panel">
						<div class="row">
						    <div class="col-md-12">
							      <!-- Widget -->
							     <header class="panel-heading">
							          <h4 class="pull-left"><i class="icon-reorder"></i>Bills</h4>
							          <div class="widget-icons pull-right">
							          </div>
							          <div class="clearfix"></div>
							    </header>     
							        <!-- Widget content -->
							    <div class="panel-body">
						          <div class="padd">
						          	<div class="row">
						        		<div class="col-sm-6 col-md-6 sub_section_border">
											<div class='sub_section_title'>New Bill</div>       
											<form action="<?php echo site_url("register-resident-bill/".$resident_id2."/".$current_page."/".$resident_receipt_id)?>" method="post" class="niceform">
						            			
						            			<input type="hidden" name="resident_id" value="<?php echo $resident_id2?>"/>
						            			<input type="hidden" name="financial_year_id" value="<?php echo $financial_year_id?>"/>
						            			<input type="hidden" name="dc" value="D"/>
						            			<input type="hidden" name="units" value="1"/>
													<fieldset>
								        			<div class="row">
						        						<div class="col-sm-6 col-md-6">
															<dl>
																<dt><label>Payment Year: </label></dt>
																<dd><input type="text" name="entry_items_year"  class="form-control" value="<?php echo date("Y");?>" /></dd>
															</dl>
						                		        </div>
						        						<div class="col-sm-6 col-md-6">
						        		             		<dl>
						                                    	Credit Note:
						                		    			<select name="dc"  class="form-control">
						                                        	<option value="D">No</option>
						                                        	<option value="C">Yes</option>
						                                        </select>
						                    				</dl>
								                        </div>
						        		            </div>
						        					<div class="row">
						        						<div class="col-sm-2 col-md-2 col-md-offset-5">
						                		    			<input type="submit" value="Create Bill" class="btn btn-success"  />
								                        </div>
						        		            </div>
													</fieldset>
											</form>
						          		</div>
						                <div class="col-sm-6 col-md-6">
										<?php 
											if(count($resident_bills) > 0){
												foreach($resident_bills as $row3):
													$resident_bill_number = $row3->resident_bill_number;
																
													$rec = "<strong>Bill No. </strong>".$resident_bill_number;
												endforeach;
											}
											else{
												$rec = "";
											}
										?>
						                	
											<div class='sub_section_title'><?php echo $rec; ?></div>       
											<?php if($resident_bill_id > 0): ?>   
											<form action="<?php echo site_url("front_office/register_bill/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id)?>" method="post" class="niceform">
						                                   
						            			<input type="hidden" name="units" value="1"/>
						            			<input type="hidden" name="resident_bill_id" value="<?php echo $resident_bill_id;?>"/>
												<fieldset>
								        			<div class="row">
						        						<div class="col-sm-6 col-md-6">
															<dl>
																<dt><label>Payment Type: </label></dt>
																<dd>
																	<select size="1" name="payment_type_id">
																		<?php
																			if(count($payment_type) > 0){
																				foreach ($payment_type as $row):
																					$payment_type_name = $row->payment_type_name;
																					$payment_type_id = $row->payment_type_id;
																					if($resident_payment_type == $payment_type_name){
																						echo "<option selected='selected' value='".$payment_type_id."'>".$payment_type_name."</option>";
																					}
																					else{
																						echo "<option value='".$payment_type_id."'>".$payment_type_name."</option>";
																					}
																				endforeach;
																			}
																		?>
																	</select>
																</dd>
															</dl>
						                		        </div>
						                                <div class="col-sm-6 col-md-6">
															<dl>
																<dt><label>Amount: </label></dt>
																<dd><input type="text" name="amount"  class="form-control" value="" /></dd>
															</dl>
								                        </div>
						                            </div>
								        			<div class="row">
						        						<div class="col-sm-6 col-md-6">
															<dl>
																<dt><label>Month from: </label></dt>
																<dd>
																	<select size="1" name="month_from"  class="form-control">
																		<?php
																			if(count($month) > 0){
																				foreach ($month as $row):
																					$month_name = $row->month_name;
																					$month_id = $row->month_id;
						//															echo "month ".date("M");
																					if(date("M") == $month_name){
																						echo "<option selected='selected' value='".$month_id."'>".$month_name."</option>";
																					}
																					else{
																						echo "<option value='".$month_id."'>".$month_name."</option>";
																					}
																				endforeach;
																			}
																		?>
																	</select>
																</dd>
															</dl>
								                        </div>
						                                <div class="col-sm-6 col-md-6">
															<dl>
																<dt><label>Month to: </label></dt>
																<dd>
																	<select size="1" name="month_to"  class="form-control">
																		<?php
																			if(count($month) > 0){
																				foreach ($month as $row):
																					$month_name = $row->month_name;
																					$month_id = $row->month_id;
						//															echo "month ".date("M");
																					if(date("M") == $month_name){
																						echo "<option selected='selected' value='".$month_id."'>".$month_name."</option>";
																					}
																					else{
																						echo "<option value='".$month_id."'>".$month_name."</option>";
																					}
																				endforeach;
																			}
																		?>
																	</select>
																</dd>
															</dl>
								                        </div>
						        		            </div>
						        					<div class="row">
						        						<div class="col-sm-2 col-md-2 col-md-offset-5">
						                		    			<input type="submit" value="Add Item" class="btn btn-success"  />
								                        </div>
						        		            </div>
													<div class="row">
						                            	<div class="col-sm-6 col-md-6">
						        		             		<table class="table table-condensed table-striped table-hover">
																<thead>
						    										<tr>
						        										<th scope="col" class="rounded-company">Delete</th>
						            									<th scope="col" class="rounded">#</th>
						            									<th scope="col" class="rounded">Month</th>
						            									<th scope="col" class="rounded">Bill Item</th>
						            									<th scope="col" class="rounded-q4">Amount</th>
						        									</tr>
						    									</thead>
						    									<tbody>
						            							<?php
																
																if(is_array($resident_bill_items))
																{
																	$count = 0;
																	$total = 0;
															
																	foreach($resident_bill_items as $row3):
																		$count++;
																		$payment = $row3->payment_type_name;
																		$id = $row3->resident_bill_item_id;
																		$month_from = $row3->month_from;
																		$month_to = $row3->month_to;
																		$amount = $row3->amount;
																		$total += $amount;
																		
																		if(count($month) > 0){
																			foreach ($month as $row):
																				$month_name = $row->month_name;
																				$month_id = $row->month_id;
																				
																				if($month_from == $month_id){
																					$month_from2 = $month_name;
																				}
																				if($month_to == $month_id){
																					$month_to2 = $month_name;
																				}
																			endforeach;
																		}
																		
																		if($month_from == $month_to){
																			$months = $month_from2;
																		}
																		else{
																			$months = $month_from2." - ".$month_to2;
																		}
																
																		echo"
																			<tr>
																				<td>
																					<a href='".site_url("/front_office/delete_resident_bill_item/".$id."/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id)."'<div class='btn-group'>
																						<img src='".base_url("images/trash.png")."' alt='' title='' border='0' />
																					</div></a>
																				</td>
																				<td>".$count."</td>
																				<td>".$months."</td>
																				<td>".$payment."</td>
																				<td>".$amount."</td>
																			</tr>
																		";
																	endforeach;
																	
																	echo"
																		<tr>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td>".$total."</td>
																		</tr>
																	";
																}
																?>
															</table>
								                        </div>
													</div>
						        					<div class="row">
						        						<div class="col-sm-2 col-md-2 col-md-offset-5"><a href="<?php echo site_url("/front_office/resident_bill/".$resident_id2."/".$current_page."/".$resident_receipt_id."/".$resident_bill_id);?>">
																	<input type="button" value="Print" class="btn btn-info" />
																</a>
								                        </div>
						        		            </div>
												</fieldset>
											</form>
											<?php endif; ?>
						         		</div>
						            </div>
						          </div>
							    </div>
							</div>
						</div>
					</section>
					<section class="panel">
						<div class="row">
						    <div class="col-md-12">
							      <!-- Widget -->
							     <header class="panel-heading">
							          <h4 class="pull-left"><i class="icon-reorder"></i>Account History</h4>
							          <div class="widget-icons pull-right">
							          	<a href= '<?php echo site_url("/front_office/resident_statement/".$resident_id2);?>' class="btn btn-sm btn-info">Print Statement</a>
							          </div>
							          <div class="clearfix"></div>
							    </header>     
							        <!-- Widget content -->
							    <div class="panel-body">
						          <div class="padd">
						          		<div class="row">
							        		<div class="col-sm-6 col-md-6 sub_section_border">
												<div class='sub_section_title'>Invoices</div>
							                        <table class="table table-condensed table-striped table-hover">
																	<thead>
							    										<tr>
							        										<th scope="col" class="rounded-company"></th>
							            									<th scope="col" class="rounded"></th>
							            									<th scope="col" class="rounded">#</th>
							            									<th scope="col" class="rounded">Date</th>
							            									<th scope="col" class="rounded">Invoice Number</th>
							            									<th scope="col" class="rounded-q4">Amount</th>
							        									</tr>
							    									</thead>
							    									<tbody>
							            							<?php
																	
																	if(is_array($past_resident_bills))
																	{
																		$count = 0;
																		$total = 0;
																		foreach($past_resident_bills as $row3):
																			$count++;
																			$bill_number = $row3->resident_bill_number;
																			$bill_date = $row3->resident_bill_date;
																			$id5 = $row3->resident_bill_id;
																			
																			$total_bill = 0;
																			foreach($bill_items as $row33):
																				$bill_amount = $row33->amount;
																				$id6 = $row33->resident_bill_id;
																				if($id6 == $id5){
																					$total_bill += $bill_amount;
																				}
																			endforeach;
																			$total += $total_bill;
																			
																			echo"
																				<tr>
																					<td>
																						<a href='#' onClick='delete_resident_bill2(".$id5.", ".$resident_id2.")'>
																							<img src='".base_url("")."img/delete.png' alt='' title='' border='0' />
																						</a>
																					</td>
																					<td>
							                		    								<a href=".site_url("/front_office/resident_bill2/".$id5."/".$resident_id2).">
																							<img src='".base_url("")."img/icons/icon-48-print.png' alt='' title='' border='0' />
																						</a>
																					</td>
																					<td>".$count."</td>
																					<td>".$bill_date."</td>
																					<td>".$bill_number."</td>
																					<td>".number_format($total_bill)."</td>
																				</tr>
																			";
																		endforeach;
																	}
																	?>
																	<tr>
																		<td colspan="5" align="right">Total: </td>
																		<td><?php echo number_format($total); ?></td>
																	</tr>
																</table>
							                    </div>
							                    <div class="col-sm-6 col-md-6">
													<div class='sub_section_title'>Receipts</div>
							        		             		<table class="table table-condensed table-striped table-hover">
																	<thead>
							    										<tr>
							        										<th scope="col" class="rounded-company"></th>
							            									<th scope="col" class="rounded"></th>
							            									<th scope="col" class="rounded">#</th>
							            									<th scope="col" class="rounded">Date</th>
							            									<th scope="col" class="rounded">Receipt Number</th>
							            									<th scope="col" class="rounded">Bankslip Number</th>
							            									<th scope="col" class="rounded">Bankslip Date</th>
							            									<th scope="col" class="rounded-q4">Amount</th>
							        									</tr>
							    									</thead>
							    									<tbody>
							            							<?php
																	
																	if(is_array($past_resident_receipts))
																	{
																		$count = 0;
																		$total = 0;
																		foreach($past_resident_receipts as $row3):
																			$count++;
																			$bankslip_date = $row3->bankslip_date;
																			$bankslip_number = $row3->bankslip_number;
																			$receipt_number = $row3->resident_receipt_number;
																			$receipt_date = $row3->resident_receipt_date;
																			//$receipt_payment = $row3->resident_receipt_payment;
																			$id5 = $row3->resident_receipt_id;
																			
																			$receipt_payment = 0;
																			foreach($receipt_items as $items){
																				$id2 = $items->resident_receipt_id;
																				
																				if($id5 == $id2){
																					$receipt_payment += $items->resident_receipt_item_payment;
																				}
																			}
																			$total += $receipt_payment;
																	
																			echo"
																				<tr>
																					<td>
																						<a href='#' onClick='delete_resident_receipt2(".$id5.", ".$resident_id2.")'>
																							<img src='".base_url("")."img/delete.png' alt='' title='' border='0' />
																						</a>
																					</td>
																					<td>
							                		    								<a href=".site_url("/front_office/resident_receipt2/".$id5."/".$resident_id2).">
																							<img src='".base_url("")."img/icons/icon-48-print.png' alt='' title='' border='0' />
																						</a>
																					</td>
																					<td>".$count."</td>
																					<td>".$receipt_date."</td>
																					<td>".$receipt_number."</td>
																					<td>".$bankslip_number."</td>
																					<td>".$bankslip_date."</td>
																					<td>".number_format($receipt_payment)."</td>
																				</tr>
																			";
																		endforeach;
																	}
																	?>
																	<tr>
																		<td colspan="7" align="right">Total: </td>
																		<td><?php echo number_format($total); ?></td>
																	</tr>
																</table>
									                        </div>
														</div>
						          </div>
							    </div>
							</div>
						</div>
					</section>
					<section class="panel">
						<div class="row">
						    <div class="col-md-12">
							      <!-- Widget -->
							     <header class="panel-heading">
							          <h4 class="pull-left"><i class="icon-reorder"></i>Resident Details</h4>
							          <div class="widget-icons pull-right">
							          </div>
							          <div class="clearfix"></div>
							    </header>     
							        <!-- Widget content -->
							    <div class="panel-body">
						          <div class="padd">
						          	<form action="<?php echo site_url('front_office/update_resident_details/'.$resident_id2."/1")?>" method="post" class="niceform">
										<fieldset>
						                	<div class="row">
						        				<div class="col-sm-4 col-md-4 sub_section_border">
													<div class='sub_section_title'>Basic Information</div>
													<dl>
														<dt><label>Resident Number: </label></dt>
														<dd><input type="text" name="resident_no"  class="form-control" value="<?php echo $number;?>" /></dd>
													</dl>
													<dl>
														<dt><label>Names: </label></dt>
														<dd><input type="text" name="resident_othernames"  class="form-control" value="<?php echo $othernames;?>" /></dd>
													</dl>
						                    
													<dl>
														<dt><label>Gender: </label></dt>
														<dd>
															<select name="gender_id"  class="form-control">
																<?php
																	if(count($gender) > 0){
																		foreach ($gender as $row):
																			$gender_name = $row->gender_name;
																			$gender_id = $row->gender_id;
																			if($resident_gender == $gender_name){
																				echo "<option selected='selected' value='".$gender_id."'>".$gender_name."</option>";
																			}
																			else{
																				echo "<option value='".$gender_id."'>".$gender_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                    
													<dl>
														<dt><label>Title: </label></dt>
														<dd>
															<select size="1" name="title_id"  class="form-control">
																<?php
																	if(count($title) > 0){
																		foreach ($title as $row):
																			$title_name = $row->title_name;
																			$title_id = $row->title_id;
																			if($resident_title == $title_name){
																				echo "<option selected='selected' value='".$title_id."'>".$title_name."</option>";
																			}
																			else{
																				echo "<option value='".$title_id."'>".$title_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                            
													<dl>
														<dt><label>Phone Number: </label></dt>
														<dd><input type="text" name="resident_phone"  class="form-control" value="<?php echo $phone;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Email Address: </label></dt>
														<dd><input type="text" name="resident_email"  class="form-control" value="<?php echo $email;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Town: </label></dt>
														<dd><input type="text" name="resident_town"  class="form-control" value="<?php echo $town;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Date of Birth: </label></dt>
														<dd><input type="text" name="resident_dob"  class="form-control" value="<?php echo $dob;?>" /></dd>
													</dl>
						                        </div>
						                        
						        				<div class="col-sm-4 col-md-4 sub_section_border">
													<div class='sub_section_title'>University Information</div>
													<dl>
														<dt><label>University: </label></dt>
														<dd>
															<select size="1" name="university_id"  class="form-control">
																<?php
																	if(count($university) > 0){
																		foreach ($university as $row):
																			$university_name = $row->university_name;
																			$university_id = $row->university_id;
																			if($resident_university == $university_name){
																				echo "<option selected='selected' value='".$university_id."'>".$university_name."</option>";
																			}
																			else{
																				echo "<option value='".$university_id."'>".$university_name."</option>";
																			}
																		endforeach;
																	}
																?>
															</select>
														</dd>
													</dl>
						                            
													<dl>
														<dt><label>Course: </label></dt>
														<dd><input type="text" name="resident_course"  class="form-control" value="<?php echo $course;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Completion Year: </label></dt>
														<dd><input type="text" name="resident_course_year"  class="form-control" value="<?php echo $course_year;?>" /></dd>
													</dl>
						                        </div>
						                        
						        				<div class="col-sm-4 col-md-4">
													<div class='sub_section_title'>Medical Information</div>
						                            
													<dl>
														<dt><label>Doctor: </label></dt>
														<dd><input type="text" name="resident_doctor"  class="form-control" value="<?php echo $doctor;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Hospital: </label></dt>
														<dd><input type="text" name="resident_hospital"  class="form-control" value="<?php echo $hospital;?>" /></dd>
													</dl>
						                            
													<dl>
														<dt><label>Hospital Location: </label></dt>
														<dd><input type="text" name="resident_hospital_location"  class="form-control" value="<?php echo $hospital_location;?>" /></dd>
													</dl>
						                        </div>
						                    </div>
						                    <div class="row">
						        						<div class="col-sm-2 col-md-2 col-md-offset-5">
						                		    			<input type="submit" value="Update" class="btn btn-success"  />
								                        </div>
						        		            </div>
										</fieldset>
									</form>
						          </div>
							    </div>
							</div>
						</div>
					</section>

	          </div>
	     </div>
	    </div>
	  </div>
  </div>
</section>
