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
								<h2>Albums List</h2>
								<p>All the albums picked for this project</p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<?php
										$sql = "SELECT year, year_id, year_status FROM jason_years ORDER BY year";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
										    // output data of each row
										    while($row = $result->fetch_assoc()) {
										    	$albumssql = "SELECT album_id, jason_artists.artist_id, source_name, album_name, artist_name, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE jason_years.year_id=".$row["year_id"]." ORDER BY source_name";
									?>
									<h3 class="major"><?php echo($row["year"]); ?></h3>
									<?php
										    	$conn1 = new mysqli($servername, $username, $password, $dbname);
												$albumsresult = $conn1->query($albumssql);
												if ($albumsresult->num_rows > 0) {
												    // output data of each row
												    while($albumsrow = $albumsresult->fetch_assoc()) {
												    	
									?>
									<p style="height:130px"><a href="viewalbum.php?album_id=<?php echo $albumsrow["album_id"]; ?>"><img src="images/albums/<?php echo $albumsrow["album_id"]; ?>.jpg" border=0 style="float:left;height:100px;width:100px;padding:5px;"></a><b><?php echo($albumsrow["source_name"]); ?>'s</b> Pick: <b><a href="viewalbum.php?album_id=<?php echo $albumsrow["album_id"]; ?>"><?php echo($albumsrow["album_name"]); ?></a></b> by <b><a href="viewartist.php?artist_id=<?php echo $albumsrow["artist_id"]; ?>"><?php echo($albumsrow["artist_name"]); ?></a></b><br>

									
									<?php
														if($row["year_status"]!="active")
														{
															$ratingsources_conn = new mysqli($servername, $username, $password, $dbname);
															// Check connection
															if ($ratingsources_conn->connect_error) {
															    die("Connection failed: " . $ratingsources_conn->connect_error);
															}
															$ratingsources_sql = "SELECT source_name, source_id FROM jason_sources WHERE source_id!=1 ORDER BY source_name";
															$ratingsources_result = $ratingsources_conn->query($ratingsources_sql);
															if ($ratingsources_result->num_rows > 0) {
															    // output data of each row
															    while($ratingsources_row = $ratingsources_result->fetch_assoc()) {
															    	$albumlength_sql="SELECT sum(duration) as album_length FROM track where album_id=".$albumsrow["album_id"];
															    	$albumlength_conn = new mysqli($servername, $username, $password, $dbname);
															    	$albumlength_result = $albumlength_conn->query($albumlength_sql);
																	if ($albumlength_result->num_rows > 0) {
																	    // output data of each row
																	    while($albumlength_row = $albumlength_result->fetch_assoc()) {
																	    	$album_length=$albumlength_row["album_length"];
																	    }
																	}
															    	$finalscore=0;
															    	$finalscore_sql="SELECT duration, rating_score, rating_category FROM jason_ratings JOIN track ON track.row_id = jason_ratings.rating_track WHERE jason_ratings.album_id=".$albumsrow["album_id"]." AND rating_source=".$ratingsources_row["source_id"];
															    	//echo($finalscore_sql);
															    	$finalscore_conn = new mysqli($servername, $username, $password, $dbname);
															    	$finalscore_result = $finalscore_conn->query($finalscore_sql);
																	if ($finalscore_result->num_rows > 0) {
																	    // output data of each row
																	    while($finalscore_row = $finalscore_result->fetch_assoc()) {
																	    	$trackscore=$finalscore_row["rating_score"];
																	    	$trackduration=$finalscore_row["duration"];
																	    	$trackweight=$trackduration/$album_length;
																	    	$adjustedscore=$trackscore*$trackweight;
																	    	$finaltrackscore=0;
																	    	switch ($finalscore_row["rating_category"]){
																	    		case "S":
																	    			$finaltrackscore=$adjustedscore;
																	    			break;
																	    		case "L":
																	    			$finaltrackscore=$adjustedscore+0.2;
																	    			break;
																	    		case "P":
																	    			$finaltrackscore=$adjustedscore+0.1;
																	    			break;
																	    		default:
																	    			$finaltrackscore=$adjustedscore;
																			}
																			$finalscore=$finalscore+$finaltrackscore;
																	    }
																	}
									?>
									<?php if($finalscore>0){echo($ratingsources_row["source_name"])."'s Rating: ".round($finalscore,2)."<br>";} ?>
									<?php
															    }
															}
															$ratingsources_conn->close();
														}
									?>
										</p>
									<?php
													}
												}				    	
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