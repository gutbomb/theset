<?php
	include("includes/login.php");
	include("includes/dbconnect.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
?>
<!DOCTYPE HTML>
<!--
	Solid State by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>The Set - Genres List</title>
		<?php include("includes/htmlhead.php"); ?>
	</head>
	<body>

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<?php
					$headerclass=NULL;
					include("includes/header.php");
				?>

				<!-- Wrapper -->
					<section id="wrapper" style="">
						<header>
							<div class="inner">
								<h2>Genres List</h2>
								<p>All the genres picked for this project</p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<?php
										$sql = "SELECT DISTINCT album_genre FROM jason_albums ORDER BY album_genre";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
										    // output data of each row
										    while($row = $result->fetch_assoc()) {
										    	$genre=$row['album_genre'];
									?>
									<a id="<?php echo(urlencode($genre)); ?>"></a><h2><?php echo($genre); ?></h2>
									<ul style="list-style-type:none;">
									<?php
										    	$sql2="SELECT album_name, artist_name, jason_albums.artist_id, album_id FROM jason_albums JOIN jason_artists ON jason_artists.artist_id=jason_albums.artist_id WHERE album_genre='".$genre."'";
										    	//echo $sql2;
										    	$conn2 = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn2->connect_error) {
												    die("Connection failed: " . $conn2->connect_error);
												}
												$result2 = $conn2->query($sql2);
												if ($result2->num_rows > 0) {
												    // output data of each row
												    while($row2 = $result2->fetch_assoc()) {
												    	$artist_id=$row2['artist_id'];
												    	$artist_name=$row2['artist_name'];
												    	$album_id=$row2['album_id'];
												    	$album_name=$row2['album_name'];
									?>
										<li><a href="viewalbum.php?album_id=<?php echo($album_id); ?>" style="text-decoration:none !important;"><img src="images/albums/<?php echo($album_id); ?>.jpg" border="0" style="width:32px;"></a> <b><a href="viewalbum.php?album_id=<?php echo($album_id); ?>"><?php echo($album_name); ?></a></b> by <b><a href="viewartist.php?artist_id=<?php echo($artist_id); ?>"><?php echo($artist_name); ?></a></b></li>
									<?php			    	
													}
												}
									?>
									</ul>
									<?php
										    }
									    }
										$conn->close();
									?>
									
									<section class="features">
										<article>
											<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
											<h3 class="major">Nisl placerat</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing vehicula id nulla dignissim dapibus ultrices.</p>
											<a href="#" class="special">Learn more</a>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
											<h3 class="major">Nisl placerat</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing vehicula id nulla dignissim dapibus ultrices.</p>
											<a href="#" class="special">Learn more</a>
										</article>
									</section>
								</div>
							</div>

					</section>


				<!-- Footer -->
					<?php include("includes/footer.php"); ?>

			</div>
	</body>
</html>