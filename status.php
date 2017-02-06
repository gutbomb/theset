<?php
	$loginrequired=1;
	include("includes/login.php");
	include("includes/dbconnect.php");
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	$sql="SELECT jason_artists.artist_id, jason_albums.album_id, artist_name, album_name, (SELECT COUNT(row_id) FROM track WHERE album_id=jason_albums.album_id) AS track_count, (SELECT COUNT(rating_id) FROM jason_ratings WHERE album_id=jason_albums.album_id AND rating_source=2) AS jason_rating_count, (SELECT COUNT(rating_id) FROM jason_ratings WHERE album_id=jason_albums.album_id AND rating_source=4) AS david_rating_count, (SELECT COUNT(review_id) FROM jason_reviews WHERE review_album=jason_albums.album_id AND review_source=2) AS jason_review_count, (SELECT COUNT(review_id) FROM jason_reviews WHERE review_album=jason_albums.album_id AND review_source=4) AS david_review_count FROM jason_albums JOIN jason_artists ON jason_artists.artist_id=jason_albums.artist_id WHERE album_year=(SELECT year_id FROM jason_years WHERE year_status='active')";
?>
<!DOCTYPE HTML>
<!--
	Solid State by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>The Set - Year Status</title>
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
								<div class="logo"><img class="icon" src="images/theset.png"></div>
								<h2>The Set - Year Status</h2>
								<p>50 Years - 150 Albums - 2 Mandelbros</p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<table>
										<tr>
											<td>Artist</td>
											<td>Album</td>
											<td>Tracks</td>
											<td>Jason's Ratings</td>
											<td>Jason's Reviews</td>
											<td>David's Ratings</td>
											<td>David's Reviews</td>
										</tr>
				<?php
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
					    // output data of each row
					    while($row = $result->fetch_assoc()) {
					    	$artist_id=$row["artist_id"];
					    	$album_id=$row["album_id"];
					    	$artist_name=$row["artist_name"];
					    	$album_name=$row["album_name"];
					    	$track_count=$row["track_count"];
					    	$jason_rating_count=$row["jason_rating_count"];
					    	$jason_review_count=$row["jason_review_count"];
					    	$david_rating_count=$row["david_rating_count"];
					    	$david_review_count=$row["david_review_count"];
				?>
										<tr>
											<td><a href="viewartist.php?artist_id=<?php echo($artist_id); ?>"><?php echo($artist_name); ?></a></td>
											<td><a href="submitratings.php?album_id=<?php echo($album_id); ?>"><?php echo($album_name); ?></a></td>
											<td><?php echo($track_count); ?></td>
											<td><?php echo($jason_rating_count); ?></td>
											<td><?php echo($jason_review_count); ?></td>
											<td><?php echo($david_rating_count); ?></td>
											<td><?php echo($david_review_count); ?></td>
										</tr>
				<?php
					    }
					}

					$conn->close();
				?>
									</table>
								</div>
							</div>

					</section>

				<!-- Footer -->
					<?php include("includes/footer.php"); ?>

			</div>
	</body>
</html>