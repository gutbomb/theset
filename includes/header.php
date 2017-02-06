				<!-- Header -->
					<header id="header"<?php echo($headerclass);?>>
						<h1><a href="./">The Set</a></h1>
						<nav>
							<a href="#menu">Menu</a>
						</nav>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<div class="inner">
							<h2>Menu</h2>
							<ul class="links">
								<li><a href="./">Home</a></li>
								<li><a href="albumslist.php">Albums</a></li>
								<li><a href="artistslist.php">Artists</a></li>
								<li><a href="genreslist.php">Genres</a></li>
								<?php
									if(isset($_SESSION["user_id"]))
									{
								?>
								<li><a href="status.php">Status</a></li>
								<li><a href="changepassword.php">Change Password</a></li>
								<li><a href="logout.php">Log Out</a></li>
								<?php	
									}
									else
									{
								?>
								<li><a href="login.php?prevurl=<?php echo($_SERVER['REQUEST_URI']);?>">Log In</a></li>
								<?php
									}
								?>
							</ul>
							<a href="#" class="close">Close</a>
						</div>
					</nav>