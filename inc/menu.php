	
    <div id="main-nav" class="row-fluid">
    
    	<div id="nav-wrapper" class="span12">
            <a class="toggleMenu" href="#">Menu</a>
            <ul id="dropdownmenu" class="nav">
            
            	<li>
            		<a href="<?php echo $STR_URL; ?>">Home</a>
            	</li>
            
            	<!-- Bookings -->
                <?php if ($admin->getModuleFile('bookings', 'list') !== 0) { ?>
            	<li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('bookings', 'list'); ?>">Bookings</a>
                	<ul>						
						<?php if ($admin->getModuleFile('bookings', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('bookings', 'add'); ?>">New Booking</a></li><?php } ?>
                        <?php if ($admin->getModuleFile('bookings', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('bookings', 'list'); ?>">Booking List</a></li><?php } ?>						
                    </ul>
                </li>
                <?php } ?>
                <!-- Bookings -->
				
				
                <?php if ($admin->getModuleFile('master_data', 'execute') !== 0) { ?>
                <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('master_data', 'execute'); ?>">Master Data</a>
                  <ul>
                  	  <?php if ($admin->getModuleFile('activities', 'list') !== 0) { ?>	
                      <li><a href="<?php echo $STR_URL; ?>#">Activities Price</a>
                      	<ul>
                        	<?php if ($admin->getModuleFile('activities', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('activities', 'add'); ?>">New Activity Price</a></li><?php } ?>
                            <?php if ($admin->getModuleFile('activities', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('activities', 'list'); ?>">Activity Price List</a></li><?php } ?>
                        </ul>
                      </li>
                      <?php } ?>
                      <!--
                      <?php if ($admin->getModuleFile('products', 'list') !== 0) { ?>  
                      <li><a href="<?php echo $STR_URL; ?>#">Products</a>
                        <ul>
                            <?php if ($admin->getModuleFile('products', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('products', 'add'); ?>">New Product</a></li><?php } ?>
                            <?php if ($admin->getModuleFile('products', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('products', 'list'); ?>">Product List</a></li><?php } ?>
                        </ul>
                      </li>
                      <?php } ?>
                      -->
                      <?php if ($admin->getModuleFile('sizes', 'list') !== 0) { ?>	
                      <li><a href="<?php echo $STR_URL; ?>#">Sizes</a>
                      	<ul>
                        	<?php if ($admin->getModuleFile('sizes', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('sizes', 'add'); ?>">New Size</a></li><?php } ?>
                            <?php if ($admin->getModuleFile('sizes', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('sizes', 'list'); ?>">Size List</a></li><?php } ?>
                        </ul>
                      </li>
                      <?php } ?>
                      <?php if ($admin->getModuleFile('stores', 'list') !== 0) { ?>	
                      <li><a href="<?php echo $STR_URL; ?>#">Stores</a>
                      	<ul>
                        	<?php if ($admin->getModuleFile('stores', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('stores', 'add'); ?>">New Store</a></li><?php } ?>
                            <?php if ($admin->getModuleFile('stores', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('stores', 'list'); ?>">Store List</a></li><?php } ?>
                        </ul>
                      </li>
                      <?php } ?>
                      
                      <?php if ($admin->getModuleFile('suppliers', 'list') !== 0) { ?>	
                      <li><a href="<?php echo $STR_URL; ?>#">Suppliers</a>
                      	<ul>
                        	<?php if ($admin->getModuleFile('suppliers', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('suppliers', 'add'); ?>">New Supplier</a></li><?php } ?>
                            <?php if ($admin->getModuleFile('suppliers', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('suppliers', 'list'); ?>">Supplier List</a></li><?php } ?>
                        </ul>
                      </li>
                      <?php } ?>
                  </ul>
            	</li>
            	<?php } ?>
            	
            	
                <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_profile', 'view'); ?>">Account</a>
                	<ul>
                    	<?php if ($admin->getModuleFile('user_profile', 'edit') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_profile', 'edit'); ?>">Edit Profile</a></li><?php } ?>
                        <?php if ($admin->getModuleFile('user_password_change', 'execute') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_password_change', 'execute'); ?>">Change Password</a></li><?php } ?>
                        <?php if ($admin->getModuleFile('user_profile', 'view') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_profile', 'view'); ?>">View Profile</a></li><?php } ?>
                        <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('file_logout', 'execute'); ?>"><strong>Logout</strong></a></li>
                    </ul>
                </li>
                
                
                <?php if ($_SESSION['user']['type'] == 'admin') { ?>
                <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('users', 'list'); ?>">System Administration</a>
                	<ul>
                    	<?php if ($admin->getModuleFile('users', 'list') !== 0) { ?>
                        <li><a href="<?php echo $STR_URL; ?>#">User</a>
                        	<ul>
                            	<?php if ($admin->getModuleFile('users', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('users', 'add'); ?>">New User</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('users', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('users', 'list'); ?>">User List</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('user_password_reset', 'execute') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_password_reset', 'execute'); ?>">Reset User Password</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('privileges', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('privileges', 'list'); ?>">Privileges</a></li><?php } ?>                                
                            </ul>
                        </li>
                        <?php } ?>
                        
                        <?php if ($admin->getModuleFile('user_groups', 'list') !== 0) { ?>
                        <li><a href="<?php echo $STR_URL; ?>#">User Group</a>
                        	<ul>
                            	<?php if ($admin->getModuleFile('user_groups', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_groups', 'add'); ?>">New User Group</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('user_groups', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('user_groups', 'list'); ?>">User Group List</a></li><?php } ?>                                
                                <?php if ($admin->getModuleFile('privileges', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('privileges', 'list'); ?>">Privileges</a></li><?php } ?>                                
                            </ul>
                        </li>
                        <?php } ?>
                        
                        <?php if ($admin->getModuleFile('modules', 'list') !== 0) { ?>
                        <li><a href="<?php echo $STR_URL; ?>#">Modules</a>
                        	<ul>
                            	<?php if ($admin->getModuleFile('modules', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('modules', 'add'); ?>">New Module</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('modules', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('modules', 'list'); ?>">Module List</a></li><?php } ?>                                
                            </ul>
                        </li>
                        <?php } ?>

                        <?php if ($admin->getModuleFile('emails', 'list') !== 0) { ?>
                        <li><a href="<?php echo $STR_URL; ?>#">Emails</a>
                          <ul>
                              <?php if ($admin->getModuleFile('emails', 'add') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('emails', 'add'); ?>">New Email</a></li><?php } ?>
                                <?php if ($admin->getModuleFile('emails', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('emails', 'list'); ?>">Email List</a></li><?php } ?>                                
                            </ul>
                        </li>
                        <?php } ?>
                        
                        <?php if ($admin->getModuleFile('logs', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('logs', 'list'); ?>">System Log</a></li><?php } ?>
                        <?php if ($admin->getModuleFile('settings', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('settings', 'list'); ?>">Configuration Settings</a></li><?php } ?>
                    </ul>                
                </li>
                <?php } ?>
                
                <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('documentation', 'list'); ?>">Help</a>
                	<ul>
                    	<?php if ($admin->getModuleFile('documentation', 'list') !== 0) { ?><li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('documentation', 'list'); ?>">Documentation</a></li><?php } ?>
                        <li><a href="<?php echo $STR_URL; ?><?php echo $admin->getModuleFile('credits', 'view'); ?>">Credit</a></li>
                    </ul>
                </li>
        	</ul>
            <br style="clear: left;" />
    	</div>

    </div> <!-- end #main-nav -->

	<div id="login-info-wrapper">
    	<div id="login-info-left">
        	<p><?php echo $html->greetUser(); ?>, <a href="<?php echo $STR_URL; ?>user_profile_view.php?user_id=<?php echo $_SESSION['user']['id'] ?>"><strong><?php echo stripslashes($db->dbIDToField('users', 'user_id', $_SESSION['user']['id'], 'user_full_name')); ?> <em>(<?php echo $_SESSION['user']['login_name']; ?>)</em></strong></a>!</p>
        </div>
        <div id="login-info-right">
        	<p><?php echo $html->convertDateTime(date("Y-m-d H:i:s")); ?> | <a class="btn btn-danger" href="<?php echo $STR_URL; ?>logout.php" onclick="return confirmLogout()"><strong>Logout</strong></a></p>
        </div>
        <br style="clear: left;" />
    </div> <!-- end #login-info-wrapper -->