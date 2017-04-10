<?php
	include("includes/login.php");
	include("includes/dbconnect.php");
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
    
	$sql = "SELECT year, year_id FROM jason_years WHERE year_status='previous'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $previousyear=$row["year"];
	        $previousyearid=$row["year_id"];
	    }
    }
    
	$sql = "SELECT year, year_id FROM jason_years WHERE year_status='active'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $activeyear=$row["year"];
	        $activeyearid=$row["year_id"];
	    }
    }
    else
    {
		$newyearsql = "SELECT year, year_id FROM jason_years WHERE year_status='incomplete' ORDER BY RAND() LIMIT 1";
		$conn6 = new mysqli($servername, $username, $password, $dbname);
		$newyearresult = $conn6->query($newyearsql);
		if ($newyearresult->num_rows > 0) {
	    // output data of each row
		    while($newyearrow = $newyearresult->fetch_assoc()) {
		        $activeyear=$newyearrow["year"];
		        $activeyearid=$newyearrow["year_id"];
		        $updatesql = "UPDATE jason_years SET year_status = 'active' WHERE year_id = '".$activeyearid."'";
		        $conn7 = new mysqli($servername, $username, $password, $dbname);
		        $conn7->query($updatesql);
		    }
		}
    }
    $ratingcount=0;
    $trackcount=0;
    $reviewcount=0;
    $expectedreviews=0;
    $count1sql = "SELECT album_id FROM jason_albums WHERE album_year='".$activeyearid."'";
	$count1result = $conn->query($count1sql);
	if ($count1result->num_rows > 0) {
	    // output data of each row
	    while($count1row = $count1result->fetch_assoc()) {
	        $count2sql="SELECT COUNT(row_id) AS tracks FROM track WHERE album_id='".$count1row["album_id"]."'";
	        $count2conn = new mysqli($servername, $username, $password, $dbname);
	        $count2result = $count2conn->query($count2sql);
	        if ($count2result->num_rows > 0) {
			    // output data of each row
			    while($count2row = $count2result->fetch_assoc()) {
			        $trackcount=$trackcount+$count2row["tracks"];
			    }
		    }
		    $count3sql="SELECT COUNT(rating_id) AS ratings FROM jason_ratings WHERE album_id='".$count1row["album_id"]."'";
	        $count3conn = new mysqli($servername, $username, $password, $dbname);
	        $count3result = $count3conn->query($count3sql);
	        if ($count3result->num_rows > 0) {
			    // output data of each row
			    while($count3row = $count3result->fetch_assoc()) {
			        $ratingcount=$ratingcount+$count3row["ratings"];
			    }
		    }
		    $count4sql="SELECT COUNT(review_id) AS reviews FROM jason_reviews WHERE review_album='".$count1row["album_id"]."'";
	        $count4conn = new mysqli($servername, $username, $password, $dbname);
	        $count4result = $count4conn->query($count4sql);
	        if ($count4result->num_rows > 0) {
			    // output data of each row
			    while($count4row = $count4result->fetch_assoc()) {
			        $reviewcount=$reviewcount+$count4row["reviews"];
			    }
		    }
		    $count5sql="SELECT COUNT(album_id) AS albums FROM jason_albums WHERE album_year='".$activeyearid."'";
	        $count5conn = new mysqli($servername, $username, $password, $dbname);
	        $count5result = $count5conn->query($count5sql);
	        if ($count5result->num_rows > 0) {
			    // output data of each row
			    while($count5row = $count5result->fetch_assoc()) {
			        $albumscount=$count5row["albums"];
			    }
		    }
	    }
    }
    //echo($trackcount." Tracks. ".$albumscount." Albums. ".$reviewcount." Reviews. ".$ratingcount. " Ratings. ".($trackcount*3)." Ratings Expected. ".($albumscount*3)." Reviews Expected.");
    $targetcount=($trackcount*2)+($albumscount*2);
    $count=$reviewcount+$ratingcount;
    if($count==$targetcount){
    	$newyearsql = "SELECT year, year_id FROM jason_years WHERE year_status='incomplete' ORDER BY RAND() LIMIT 1";
		$conn6 = new mysqli($servername, $username, $password, $dbname);
		$newyearresult = $conn6->query($newyearsql);
		if ($newyearresult->num_rows > 0) {
	    // output data of each row
		    while($newyearrow = $newyearresult->fetch_assoc()) {
		    	$updatesql = "UPDATE jason_years SET year_status = 'complete' WHERE year_id = '".$previousyearid."'";
		        $conn7 = new mysqli($servername, $username, $password, $dbname);
		        $conn7->query($updatesql);
		    	$updatesql = "UPDATE jason_years SET year_status = 'previous' WHERE year_id = '".$activeyearid."'";
		        $conn7 = new mysqli($servername, $username, $password, $dbname);
		        $conn7->query($updatesql);
		        $activeyear=$newyearrow["year"];
		        $activeyearid=$newyearrow["year_id"];
		        $updatesql = "UPDATE jason_years SET year_status = 'active' WHERE year_id = '".$activeyearid."'";
		        $conn7 = new mysqli($servername, $username, $password, $dbname);
		        $conn7->query($updatesql);
		    }
		}
		$to      = 'gutbomb@gmail.com,keith.messier@icloud.com';
		$subject = 'The Set - Ratings and Reviews for '.$activeyear.' are in';
		$message = 'Hello,\n\rAll ratings reviews for '.$activeyear.' are in.  Visit http://gutbomb.net/theset/ to check them out and see what year we\'ll be doing this week!';
		$headers = 'From: gutbomb@gmail.com' . "\r\n" .
		    'Reply-To: gutbomb@gmail.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		header("Location:./");
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
		<title>The Set</title>
		<?php include("includes/htmlhead.php"); ?>
	</head>
	<body>

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<?php
					$headerclass=" class=\"alt\"";
					include("includes/header.php");
				?>

				<!-- Banner -->
					<section id="banner">
						<div class="inner">
							<div class="logo"><img class="icon" src="images/theset.png"></div>
							<h2>The Set</h2>
							<p>50 Years - 100 Albums - 2 Mandelbros</p>
						</div>
					</section>

				<!-- Wrapper -->
					<section id="wrapper">

						<!-- One -->
							<section id="one" class="wrapper spotlight style1">
								<div class="inner">
									<a href="#" class="image"><img src="images/pic01.jpg" alt="" /></a>
									<div class="content">
										<h2 class="major">How it works</h2>
										<p>The site assigns us a year every week.  During that week our mission is to pick our favorite album released that year.  We both listen to all 3 of the selections, give our thoughts and ratings.  The site compiles interesting analysis to our impressions, and like nerds, we enjoy</p>
										<?php
											if(isset($_SESSION["user_id"]))
											{
										?>
										<a href="logout.php" class="special">Log Out</a>
										<?php	
											}
											else
											{
										?>
										<a href="login.php?prevurl=<?php echo($_SERVER['REQUEST_URI']);?>" class="special">Login</a>
										<?php
											}
										?>
									</div>
								</div>
							</section>

						<!-- Two -->
							<section id="two" class="wrapper alt spotlight style2">
								<div class="inner">
									<a href="#" class="image"><img src="images/years/<?php echo($previousyear); ?>.jpg" alt="<?php echo($previousyear); ?>" /></a>
									<div class="content">
<h2 class="major">Last week's assignment: <?php echo($previousyear); ?></h2>
										<?php
												$prevalbumssql = "SELECT album_id, jason_artists.artist_id, source_name, album_name, artist_name, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE jason_years.year_id=".$previousyearid;
											    	$conn = new mysqli($servername, $username, $password, $dbname);
													$prevalbumsresult = $conn->query($prevalbumssql);
													if ($prevalbumsresult->num_rows > 0) {
													    // output data of each row
													    while($prevalbumsrow = $prevalbumsresult->fetch_assoc()) {
										?>
										<h3><?php echo $prevalbumsrow["source_name"]; ?>'s Pick: <a href="viewalbum.php?album_id=<?php echo $prevalbumsrow["album_id"]; ?>"><?php echo $prevalbumsrow["album_name"]; ?></a> by <a href="viewartist.php?artist_id=<?php echo $prevalbumsrow["album_id"]; ?>"><?php echo $prevalbumsrow["artist_name"]; ?></a></h3>
										<p style="height:130px">
											<a href="viewalbum.php?album_id=<?php echo $prevalbumsrow["album_id"]; ?>"><img src="images/albums/<?php echo $prevalbumsrow["album_id"]; ?>.jpg" border=0 style="float:left;height:100px;width:100px;padding:5px;"></a>
										<?php
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
															    	$albumlength_sql="SELECT sum(duration) as album_length FROM track where album_id=".$prevalbumsrow["album_id"];
															    	$albumlength_conn = new mysqli($servername, $username, $password, $dbname);
															    	$albumlength_result = $albumlength_conn->query($albumlength_sql);
																	if ($albumlength_result->num_rows > 0) {
																	    // output data of each row
																	    while($albumlength_row = $albumlength_result->fetch_assoc()) {
																	    	$album_length=$albumlength_row["album_length"];
																	    }
																	}
															    	$finalscore=0;
															    	$ratingcount=0;
															    	$finalscore_sql="SELECT duration, rating_score, rating_category FROM jason_ratings JOIN track ON track.row_id = jason_ratings.rating_track WHERE jason_ratings.album_id=".$prevalbumsrow["album_id"]." AND rating_source=".$ratingsources_row["source_id"];
															    	//echo($finalscore_sql);
															    	$finalscore_conn = new mysqli($servername, $username, $password, $dbname);
															    	$finalscore_result = $finalscore_conn->query($finalscore_sql);
																	if ($finalscore_result->num_rows > 0) {
																	    // output data of each row
																	    while($finalscore_row = $finalscore_result->fetch_assoc()) {
																			$finalscore=$finalscore+$finalscore_row["rating_score"];
																			$ratingcount++;
																	    }
																	    $finalscore=$finalscore/$ratingcount;
																	}
										?>
										<?php echo($ratingsources_row["source_name"])."'s Rating: ".round($finalscore,2)."<br>"; ?>
										<?php
															    }
															}
															$ratingsources_conn->close();
										?>
										</p>
										<?php
													    }
												    }

										?>
									</div>
								</div>
							</section>

						<!-- Three -->
							<section id="three" class="wrapper spotlight style3">
								<div class="inner">
									<a href="#" class="image"><img src="images/years/<?php echo($activeyear); ?>.jpg" alt="<?php echo($activeyear); ?>" /></a>
									<div class="content">
										<h2 class="major">This week's assignment: <?php echo($activeyear); ?></h2>
										<?php
												$activealbumssql = "SELECT album_id, jason_artists.artist_id, source_name, album_name, artist_name, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE jason_years.year_id=".$activeyearid;
											    	$conn = new mysqli($servername, $username, $password, $dbname);
													$activealbumsresult = $conn->query($activealbumssql);
													if ($activealbumsresult->num_rows > 0) {
													    // output data of each row
													    while($activealbumsrow = $activealbumsresult->fetch_assoc()) {
										?>
										<h3><?php echo $activealbumsrow["source_name"]; ?>'s Pick: <a href="submitratings.php?album_id=<?php echo $activealbumsrow["album_id"]; ?>"><?php echo $activealbumsrow["album_name"]; ?></a> by <a href="viewartist.php?artist_id=<?php echo $activealbumsrow["album_id"]; ?>"><?php echo $activealbumsrow["artist_name"]; ?></a></h3>
										<p style="height:130px"><a href="submitratings.php?album_id=<?php echo $activealbumsrow["album_id"]; ?>"><img src="images/albums/<?php echo $activealbumsrow["album_id"]; ?>.jpg" border=0 style="float:right;height:100px;width:100px;padding:5px;"></a></p>
										<?php
													    }
												    }
										?>
									</div>
								</div>
							</section>

						<!-- Four -->
							<section id="four" class="wrapper alt style1">
								<div class="inner">
									<h2 class="major">Vital Statistics</h2>
									<p>Cras mattis ante fermentum, malesuada neque vitae, eleifend erat. Phasellus non pulvinar erat. Fusce tincidunt, nisl eget mattis egestas, purus ipsum consequat orci, sit amet lobortis lorem lacus in tellus. Sed ac elementum arcu. Quisque placerat auctor laoreet.</p>
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
										<article>
											<a href="#" class="image"><img src="images/pic06.jpg" alt="" /></a>
											<h3 class="major">Ante fermentum</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing vehicula id nulla dignissim dapibus ultrices.</p>
											<a href="#" class="special">Learn more</a>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic07.jpg" alt="" /></a>
											<h3 class="major">Fusce consequat</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing vehicula id nulla dignissim dapibus ultrices.</p>
											<a href="#" class="special">Learn more</a>
										</article>
									</section>
									<ul class="actions">
										<li><a href="#" class="button">Browse All</a></li>
									</ul>
								</div>
							</section>

					</section>

				<!-- Footer -->
					<section id="footer">
						<div class="inner">
							<ul class="copyright">
								<li>&copy; 2016 Jason Merrill All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
					</section>

			</div>
	</body>
</html>