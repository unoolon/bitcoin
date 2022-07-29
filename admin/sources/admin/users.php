<?php
$b = protect($_GET['b']);

if($b == "edit") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM btc_users WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=users"); }
	$row = $query->fetch_assoc();
	?>
	<ol class="breadcrumb">
		<li><a href="./"><?php echo $settings['name']; ?> Administrator</a></li>
		<li><a href="./?a=users">Users</a></li>
		<li class="active">Edit user</li>
	</ol>

	<div class="panel panel-default">
		<div class="panel-heading">
			Edit user
		</div>
		<div class="panel-body">
			<?php
			if(isset($_POST['btn_save'])) {
				$username = protect($_POST['username']);
				$email = protect($_POST['email']);
				$newpass = protect($_POST['newpass']);
				$status = protect($_POST['status']);
				$mobile_number = protect($_POST['mobile_number']);
				if(isset($_POST['email_verified'])) { $email_verified = '1'; } else { $email_verified = '0'; }
				if(isset($_POST['document_verified'])) { $document_verified = '1'; } else { $document_verified = '0'; }
				if(isset($_POST['mobile_verified'])) { $mobile_verified = '1'; } else { $mobile_verified = '0'; } 
				$check_u = $db->query("SELECT * FROM btc_users WHERE username='$username'");
				$check_e = $db->query("SELECT * FROM btc_users WHERE email='$email'");
				if(empty($username) or empty($email) or empty($status)) { echo error("Please enter username,email and status."); }
				elseif($row['username'] !== $username && $check_u->num_rows>0) { echo error("This username is already used. Please choose another."); }
				elseif($row['email'] !== $email && $check_e->num_rows>0) { echo errro("This email address is already used. Please choose another."); }
				else {
					if($newpass) { $npass = md5($newpass); } else { $npass = $row['password']; } 
					$update = $db->query("UPDATE btc_users SET username='$username',email='$email',password='$npass',status='$status',mobile_number='$mobile_number',email_verified='$email_verified',document_verified='$document_verified',mobile_verified='$mobile_verified' WHERE id='$row[id]'");
					echo success("Your changes was saved successfully.");
					$query = $db->query("SELECT * FROM btc_users WHERE id='$id'");
					$row = $query->fetch_assoc();
				}
			}
			?>
			
			<form action="" method="POST">
				<div class="form-group">
					<label>Username</label>
					<input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>">
				</div>
				<div class="form-group">
					<label>Email address</label>
					<input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>">
				</div>
				<div class="form-group">
					<label>New password</label>
					<input type="text" class="form-control" name="newpass" placeholder="Leave empty if do not want to change it.">
				</div>
				<div class="form-group">
					<label>Status</label>
					<select class="form-control" name="status">
						<option value="1" <?php if($row['status'] == "1") { echo 'selected'; } ?>>Verified</option>
						<option value="2" <?php if($row['status'] == "2") { echo 'selected'; } ?>>Banned</option>
						<option value="3" <?php if($row['status'] == "3") { echo 'selected'; } ?>>Not Verified</option>
						<option value="666" <?php if($row['status'] == "666") { echo 'selected'; } ?>>Administrator</option>
					</select>
				</div>
				<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
			</form>
		</div>
	</div>
	<?php
} elseif($b == "delete") {
	$id = protect($_GET['id']);
	$query = $db->query("SELECT * FROM btc_users WHERE id='$id'");
	if($query->num_rows==0) { header("Location: ./?a=users"); }
	$row = $query->fetch_assoc();
	?>
	<ol class="breadcrumb">
		<li><a href="./"><?php echo $settings['name']; ?> Administrator</a></li>
		<li><a href="./?a=users">Users</a></li>
		<li class="active">Delete user</li>
	</ol>

	<div class="panel panel-danger">
		<div class="panel-heading">
			Delete user
		</div>
		<div class="panel-body">
			<?php
			if(isset($_GET['confirm'])) {
				$delete = $db->query("DELETE FROM btc_users WHERE id='$row[id]'");;
				echo success("User <b>$row[username]</b> was deleted.");
			} else {
				echo info("Are you sure you want to delete user <b>$row[username]</b>?<br/><small>Once you do this action it can not be undone.</small>");
				echo '<a href="./?a=users&b=delete&id='.$row[id].'&confirm=1" class="btn btn-success"><i class="fa fa-check"></i> Yes</a>&nbsp;&nbsp;
					<a href="./?a=users" class="btn btn-danger"><i class="fa fa-times"></i> No</a>';
			}
			?>
		</div>
	</div>
	<?php
} else {
?>
<ol class="breadcrumb">
	<li><a href="./"><?php echo $settings['name']; ?> Administrator</a></li>
	<li class="active">Users</li>
</ol>

<div class="panel panel-default">
	<div class="panel-heading">
		Users
		<span class="pull-right">
			<form action="" method="POST">
				<input type="text" class="input_search" name="qry" placeholder="Search...">
			</form>
		</span>
	</div>
	<div class="panel-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="5%">ID</th>
					<th width="15%">Username</th>
					<th width="15%">Email</th>
					<th width="10%">Status</th>
					<th width="10%">Wallet addresses</th>
					<th width="10%">Balance</th>
					<th width="5%">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$searching=0;
				if(isset($_POST['qry'])) {
					$searching=1;
					$qry = protect($_POST['qry']);
				}
				$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
				$limit = 20;
				$startpoint = ($page * $limit) - $limit;
				if($page == 1) {
					$i = 1;
				} else {
					$i = $page * $limit;
				}
				$statement = "btc_users";
				if($searching==1) {
					if(empty($qry)) {
						$qry = 'empty query';
					}
					$query = $db->query("SELECT * FROM {$statement} WHERE id LIKE '%$qry%' or username LIKE '%$qry%' or email LIKE '%$qry%' ORDER BY id");
				} else {
					$query = $db->query("SELECT * FROM {$statement} ORDER BY id LIMIT {$startpoint} , {$limit}");
				}
				if($query->num_rows>0) {
					while($row = $query->fetch_assoc()) {
						?>
						<tr>
							<td><?php echo $row['id']; ?></td>
							<td><?php echo $row['username']; ?></td>
							<td><?php echo $row['email']; ?></td>
							<td>
								<?php
								if($row['status'] == "1") {
									echo '<span class="label label-success">Verified</span>';
								} elseif($row['status'] == "2") {
									echo '<span class="label label-danger">Banned</span>';
								} elseif($row['status'] == "3") {
									echo '<span class="label label-warning">Not Verified</span>';
								} elseif($row['status'] == "666") {
									echo '<span class="label label-info">Administrator</label>';
								} elseif($row['status'] == "777") {
									echo '<span class="label label-primary">Operator</label>';
								} else {
									echo '<span class="label label-default">Unknown</span>';
								}
								?>
							</td>
							<td><?php $nums = $db->query("SELECT * FROM btc_users_addresses WHERE uid='$row[id]'"); echo $nums->num_rows; ?></td>
							<td><a href="./?a=transactions&b=by_user&uid=<?php echo $row['id']; ?>"><?php echo get_user_balance_btc($row['id']); ?> BTC</a></td>
							<td>
								<a href="./?a=users&b=edit&id=<?php echo $row['id']; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
								<a href="./?a=users&b=delete&id=<?php echo $row['id']; ?>" title="Delete"><i class="fa fa-times"></i></a>
							</td>
						</tr>
						<?php
					}
				} else {
					if($searching == "1") {
						echo '<tr><td colspan="8">No found results for <b>'.$qry.'</b>.</td></tr>';
					} else {
						echo '<tr><td colspan="8">Still no have exchanges.</td></tr>';
					}
				}
				?>
			</tbody>
		</table>
		<?php
		if($searching == "0") {
			$ver = "./?a=users";
			if(admin_pagination($statement,$ver,$limit,$page)) {
				echo admin_pagination($statement,$ver,$limit,$page);
			}
		}
		?>
	</div>
</div>
<?php
}
?>