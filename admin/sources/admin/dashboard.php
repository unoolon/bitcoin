<ol class="breadcrumb">
	<li><a href="./"><?php echO $settings['name']; ?> Administrator</a></li>
	<li class="active">Dashboard</li>
</ol>

<div class="row">
	<div class="col-lg-3">
		 <div class="panel panel-default twitter">
                    <div class="panel-body fa-icons">
                        <small class="social-title">Users</small>
                        <h3 class="count">
                            <?php $get_stats = $db->query("SELECT * FROM btc_users"); echo $get_stats->num_rows; ?></h3>
                        <i class="fa fa-users"></i>
                    </div>
                </div>
	</div>
	<div class="col-lg-3">
		<div class="panel panel-default google-plus">
                    <div class="panel-body fa-icons">
                        <small class="social-title">Wallet Addresses</small>
                        <h3 class="count">
                            <?php $get_stats = $db->query("SELECT * FROM btc_users_addresses"); echo $get_stats->num_rows; ?></h3>
                        <i class="fa fa-globe"></i>
                    </div>
                </div>
	</div>
	<div class="col-lg-3">
		<div class="panel panel-default facebook-like">
                    <div class="panel-body fa-icons">
                        <small class="social-title">Total BTC in Website</small>
                        <h3 class="count">
                            <?php echo get_total_btc(); ?></h3>
                        <i class="fa fa-bitcoin"></i>
                    </div>
                </div>
	</div>
	<div class="col-lg-3">
		<div class="panel panel-default visitor">
                    <div class="panel-body fa-icons">
                        <small class="social-title">Your profit</small>
                        <h3 class="count" style="font-size:25px;padding-top:6px;padding-bottom:6px;">
                            <?php echo admin_get_profit(); ?></h3>
                        <i class="fa fa-dollar"></i>
                    </div>
                </div>
	</div>
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest transactions</div>
			<div class="panel-body">
				<?php
				$query = $db->query("SELECT * FROM btc_users_transactions ORDER BY id DESC LIMIT 10");
				if($query->num_rows>0) {
					while($row = $query->fetch_assoc()) {
						?>
						<div class="panel panel-default">
										<div class="panel-body">
											<div class="row">
												<div class="col-md-2 text-center">
													<?php
													if($row['type'] == "sent") {
														echo '<span class="text text-danger text-center"><i class="fa fa-arrow-circle-o-up fa-2x"></i><br/>Sent</span>';
													} else {
														echo '<span class="text text-success text-center"><i class="fa fa-arrow-circle-o-down fa-2x"></i><br/>Received</span>';
													}
													?>
													<br><br>
													<span class="text-muted"><small><?php echo $row['confirmations']." confirmations"; ?></small></span>
												</div>
												<div class="col-md-10">
													<table class="table table-striped">
														<tbody>
															<tr>
																<td>User:</td>
																<td><a href="./?a=transactions&b=by_user&uid=<?php echo $row['uid']; ?>"><?php echo idinfo($row['uid'],"username"); ?></a></td>
															</tr>
															<tr>
																<td>Transaction:</td>
																<td><a href="https://chain.so/tx/BTC/<?php echo $row['txid']; ?>"><?php echo $row['txid']; ?></a></td>
															</tr>	
															<tr>
																<td>Sender:</td>
																<td><?php echo $row['sender']; ?></td>
															</tr>
															<tr>
																<td>Recipient:</td>
																<td><?php echo $row['recipient']; ?></td>
															</tr>
															<tr>
																<td>Amount:</td>
																<td><?php echo $row['amount']; ?> BTC</td>
															</tr>
															<tr>
																<td>Time:</td>
																<td><?php echo date("d/m/Y H:i",$row['time']); ?></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
						<?php
					}
				} else {
					echo info("Still no have transactions.");
				}
				?>
			</div>	
		</div>
		
				
	</div>
</div>