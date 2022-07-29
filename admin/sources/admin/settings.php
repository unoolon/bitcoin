<ol class="breadcrumb">
	<li><a href="./"><?php echo $settings['name']; ?> Administrator</a></li>
	<li class="active">Web Settings</li>
</ol>

<div class="panel panel-default">
	<div class="panel-heading">
		Web Settings
	</div>
	<div class="panel-body">
		<?php
		if(isset($_POST['btn_save'])) {
			$title = protect($_POST['title']);
			$description = protect($_POST['description']);
			$keywords = protect($_POST['keywords']);
			$name = protect($_POST['name']);
			$url = protect($_POST['url']);
			$infoemail = protect($_POST['infoemail']);
			$supportemail = protect($_POST['supportemail']);
			$withdrawal_comission = protect($_POST['withdrawal_comission']);
			$max_addresses_per_account = protect($_POST['max_addresses_per_account']);
			$fb_link = protect($_POST['fb_link']);
			$tw_link = protect($_POST['tw_link']);
			if(empty($title) or empty($description) or empty($keywords) or empty($name) or empty($url) or empty($infoemail) or empty($supportemail)) {
				echo error("All fields are required."); 
			} elseif(!isValidURL($url)) { 
				echo error("Please enter valid site url address.");
			} elseif(!isValidEmail($infoemail)) { 
				echo error("Please enter valid info email address.");
			} elseif(!isValidEmail($supportemail)) { 
				echo error("Please enter valid support email address.");
			}  elseif(!is_numeric($withdrawal_comission)) { 
				echo error("Please enter withdrawal comission with numbers.");
			} elseif(!is_numeric($max_addresses_per_account)) { 
				echo error("Please enter max addresses per account with numbers.");
			} elseif(!empty($fb_link) && !isValidURL($fb_link)) {
				echo error("Please enter valid Facebook profile url.");
			} elseif(!empty($tw_link) && !isValidURL($tw_link)) {
				echo error("Please enter valid Twitter profile url.");
			} else {
				$update = $db->query("UPDATE btc_settings SET title='$title',description='$description',keywords='$keywords',name='$name',url='$url',infoemail='$infoemail',supportemail='$supportemail',withdrawal_comission='$withdrawal_comission',max_addresses_per_account='$max_addresses_per_account',fb_link='$fb_link',tw_link='$tw_link'");
				$settingsQuery = $db->query("SELECT * FROM btc_settings ORDER BY id DESC LIMIT 1");
				$settings = $settingsQuery->fetch_assoc();
				echo success("Your changes was saved successfully.");
			}
		}
		?>
		<form action="" method="POST">
			<div class="form-group">
				<label>Title</label>
				<input type="text" class="form-control" name="title" value="<?php echo $settings['title']; ?>">
			</div>
			<div class="form-group">
				<label>Description</label>
				<textarea class="form-control" name="description" rows="2"><?php echo $settings['description']; ?></textarea>
			</div>
			<div class="form-group">
				<label>Keywords</label>
				<textarea class="form-control" name="keywords" rows="2"><?php echo $settings['keywords']; ?></textarea>
			</div>
			<div class="form-group">
				<label>Site name</label>
				<input type="text" class="form-control" name="name" value="<?php echo $settings['name']; ?>">
			</div>
			<div class="form-group">
				<label>Site url address</label>
				<input type="text" class="form-control" name="url" value="<?php echo $settings['url']; ?>">
			</div>
			<div class="form-group">
				<label>Info email address</label>
				<input type="text" class="form-control" name="infoemail" value="<?php echo $settings['infoemail']; ?>">
			</div>
			<div class="form-group">
				<label>Support email address</label>
				<input type="text" class="form-control" name="supportemail" value="<?php echo $settings['supportemail']; ?>">
			</div>
			<div class="form-group">
				<label>Comission when client withdrawal or send Bitcoins to other address</label>
				<div class="input-group">
					<input type="text" class="form-control" name="withdrawal_comission" value="<?php echo $settings['withdrawal_comission']; ?>">
					<span class="input-group-addon" id="basic-addon2">BTC</span>
				</div>
				<small>This comission automatically will be transfered in your Bitcoin address setuped in <a href="./?a=api_keys">Block.io API Keys</a>.</small>
			</div>
			<div class="form-group">
				<label>Max wallet addresses per account</label>
				<input type="text" class="form-control" name="max_addresses_per_account" value="<?php echo $settings['max_addresses_per_account']; ?>">
			</div>	
			<div class="form-group">
				<label>Facebook profile url</label>
				<input type="text" class="form-control" name="fb_link" value="<?php echo $settings['fb_link']; ?>">
			</div>	
			<div class="form-group">
				<label>Twitter profile url</label>
				<input type="text" class="form-control" name="tw_link" value="<?php echo $settings['tw_link']; ?>">
			</div>
			<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
		</form>
	</div>
</div>