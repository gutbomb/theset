<?php
	include("includes/login.php");
	include("includes/dbconnect.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT artist_name FROM jason_artists WHERE artist_id=".$_GET["artist_id"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		    // output data of each row
		    $artist_name=$row["artist_name"];
		}
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
		<title>The Set - Albums List</title>
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
								<h2><?php echo($artist_name); ?></h2>
								<p></p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<?php
										$sql = "SELECT year, year_id FROM jason_years ORDER BY year";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
										    // output data of each row
										    while($row = $result->fetch_assoc()) {
										    	$albumssql = "SELECT album_id, jason_artists.artist_id, source_name, album_name, artist_name, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE jason_years.year_id=".$row["year_id"]." AND jason_albums.artist_id='".$_GET["artist_id"]."' ORDER BY source_name";
					
										    	$conn1 = new mysqli($servername, $username, $password, $dbname);
												$albumsresult = $conn1->query($albumssql);
												if ($albumsresult->num_rows > 0) {
									?>
									<h3 class="major"><?php echo($row["year"]); ?></h3>
									<?php
												    // output data of each row
												    while($albumsrow = $albumsresult->fetch_assoc()) {
												    	
									?>
									<p style="height:130px"><a href="viewalbum.php?album_id=<?php echo $albumsrow["album_id"]; ?>"><img src="images/albums/<?php echo $albumsrow["album_id"]; ?>.jpg" border=0 style="float:left;height:100px;width:100px;padding:5px;"></a><b><?php echo($albumsrow["source_name"]); ?>'s</b> Pick: <b><a href="viewalbum.php?album_id=<?php echo $albumsrow["album_id"]; ?>"><?php echo($albumsrow["album_name"]); ?></a></b></p>
									<?php
													}
												}				    	
										    }
									    }
										$conn->close();
									?>
									<section class="features">
										<article>
											<a href="#" class="image"><img src="images/pic04.jpg" alt="" /></a>
											<h3 class="major">Sed feugiat lorem</h3>
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