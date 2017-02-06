<?php
	include("includes/dbconnect.php");
	include("includes/login.php");

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT id_fix, jason_artists.artist_id, year_status, album_blurb, source_name, album_name, year, artist_name, album_genre FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE album_id=".$_GET["album_id"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$artist_id=$row["artist_id"];
	    	$albumtitle=$row["album_name"];
			$albumartist=$row["artist_name"];
	    	$albumyear=$row["year"];
	    	$albumgenre=$row["album_genre"];
	    	$albumsource=$row["source_name"];
	    	$albumblurb=$row["album_blurb"];
	    	$id_fix=$row["id_fix"];
	    	switch($row["year_status"]) {
	    		case "active":
	    			$hide_ratings=1;
					$hide_comments=1;
	    			break;
	    		case "incomplete":
	    			$hide_ratings=1;
	    			$hide_comments=1;
	    			break;
	    		case "previous":
	    			$hide_ratings=0;
	    			$hide_comments=0;
	    			break;
	    		case "complete":
	    			$hide_ratings=0;
	    			$hide_comments=0;
	    			break;
	    		default:
	    			$hide_ratings=0;
	    			$hide_comments=0;
	    	}
	    }
	}
	$conn->close();


?>
<!DOCTYPE HTML>
<!--
	Solid State by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>The Set - <?php echo($albumtitle); ?> by <?php echo($albumartist); ?></title>
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
						<header style="background-image: linear-gradient(to top, rgba(46, 49, 65, 0.8), rgba(46, 49, 65, 0.8)), url('images/albums/<?php echo($_GET["album_id"]) ?>.jpg') !important;background-size: auto, cover;background-position: center, 0% 30%;margin-bottom: -6.5em;">
							<div class="inner">
								<h2><?php echo($albumtitle); ?> by <a href="viewartist.php?artist_id=<?php echo($artist_id); ?>"><?php echo($albumartist); ?></a></h2>
								<p><a href="genreslist.php#<?php echo(urlencode($albumgenre)); ?>"><?php echo($albumgenre); ?></a> album released in <?php echo($albumyear); ?> | Picked by <?php echo($albumsource); ?></p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">

									<h3 class="major">From Wikipedia</h3>
									<p><?php echo(nl2br($albumblurb)); ?></p>

									<h3 class="major">Track Listing</h3>
										<table>
											<tr>
												<td>#</td>
												<td>Name</td>
												<td>David</td>
												<td>Jason</td>
											</tr>
												
											<?php
												$tracknumber=1;
												$conn = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												if($id_fix==1)
												{
													$order_by="track_number";
												}
												else
												{	
													$order_by="row_id";
												}
												$album_length=0;
												$sql = "SELECT track_name, row_id FROM track WHERE album_id=".$_GET["album_id"]." ORDER BY ".$order_by;
												$result = $conn->query($sql);
												if ($result->num_rows > 0) {
												    // output data of each row
												    while($row = $result->fetch_assoc()) {
											?>
											<tr>
												<td><?php echo($tracknumber); ?></td>
												<td><?php echo($row["track_name"]); ?></td>
											<?php
														if($hide_ratings==0)
														{
															$sourcessql = "SELECT source_id, source_name FROM jason_sources ORDER BY source_name";
															$sourcesconn = new mysqli($servername, $username, $password, $dbname);
															$sourcesresult = $sourcesconn->query($sourcessql);
															if ($sourcesresult->num_rows > 0) {
																while($sourcesrow = $sourcesresult->fetch_assoc()) {
																	$conn2 = new mysqli($servername, $username, $password, $dbname);

																	// Check connection
																	if ($conn2->connect_error) {
																	    die("Connection failed: " . $conn->connect_error);
																	}
																	$ratingsql = "SELECT rating_score FROM jason_ratings WHERE rating_track=".$row["row_id"]." AND rating_source=".$sourcesrow["source_id"];
																	$ratingresult = $conn2->query($ratingsql);
																	if ($ratingresult->num_rows > 0) {
																	    // output data of each row
																	    while($ratingrow = $ratingresult->fetch_assoc()) {
											?>
													<td><?php echo($ratingrow["rating_score"]); ?></td>
											<?php
																		}
																	}
																	else
																	{
											?>
													<td></td>
											<?php																			
																	}
																}
														
															}
														}
														else
														{
											?>
													<td></td><td></td>
											<?php
														}
											?>
											</tr>
											<?php
														$tracknumber=$tracknumber+1;
												    }
												}
												$conn->close();
											?>
										</table>
										<?php	
											if($hide_ratings==0)
											{
												$conn = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "SELECT review_text, source_name, source_id FROM jason_reviews JOIN jason_sources ON jason_sources.source_id = jason_reviews.review_source WHERE review_album=".$_GET["album_id"]." ORDER BY source_name";
												$result = $conn->query($sql);
												if ($result->num_rows > 0) {
												    // output data of each row
												    while($row = $result->fetch_assoc()) {
												    	$finalscore=0;
												    	$ratingcount=0;
												    	$finalscore_sql="SELECT rating_score FROM jason_ratings JOIN track ON track.row_id = jason_ratings.rating_track WHERE jason_ratings.album_id=".$_GET["album_id"]." AND rating_source=".$row["source_id"];
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
										<h3 class="major"><?php echo($row["source_name"]); ?>'s Review - Rating: <?php echo(round($finalscore,2)); ?></h3>
										<p><?php echo(nl2br($row["review_text"])); ?></p>
										<?php
												    }
												}
												$conn->close();
											}
											if($hide_comments==0)
											{
										?>
										<div id="comments"><h3 class="major">Comments</h3>
										<?php
												$conn = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "SELECT comment_text, source_name, source_id FROM jason_comments JOIN jason_sources ON jason_sources.source_id = jason_comments.comment_source WHERE comment_album=".$_GET["album_id"]." ORDER BY comment_date";
												$result = $conn->query($sql);
												if ($result->num_rows > 0) {
												    // output data of each row
												    while($row = $result->fetch_assoc()) {
										?>
										<h4><?php echo($row["source_name"]); ?></h4>
										<p><?php echo(nl2br($row["comment_text"])); ?></p> 
										<?php
												    }
												}
										?>
										</div>
										<?php
												if(isset($_SESSION["user_id"]))
												{
										?>
										<script type="text/javascript">
											$(document).ready(function () {
											    $('#commentform').on('submit', function(e) {
											        e.preventDefault();
											        $.ajax({
											            url : $(this).attr('action') || window.location.pathname,
											            type: "POST",
											            data: $(this).serialize(),
											            success: function (data) {
											                $("#form_output").html(data);
											                newcomments=$('#comments').html()+data;
											                $('#comment_text').val('');
											                $('#comments').html(newcomments);
											            },
											            error: function (jXHR, textStatus, errorThrown) {
											                alert(errorThrown);
											            }
											        });
											    });
											});
										</script>
										<form action="docomment.php" method="post" id="commentform">
											<div class="12u$">
												<textarea name="comment_text" id="comment_text" rows="6"></textarea>
											</div>
											<div class="12u$">
												<ul class="actions">
													<input type="hidden" name="album_id" value="<?php echo($_GET["album_id"]); ?>">
													<li><input id="submitcomment" type="submit" value="Post Comment" class="special" /></li>
													<li><input type="reset" value="Reset" /></li>
												</ul>
											</div>
										</form>
										<?php
												}
											}
										?>
									
										<?php
											if($hide_comments==0)
											{
										?>
									<section class="features">
										<article>
											<a href="#" class="image"><img src="images/pic04.jpg" alt="" /></a>
											<h3 class="major">Top Songs by Average Rating</h3>
											<table>
										<?php
												$conn = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql="SELECT distinct track_name, (SELECT AVG(rating_score)FROM jason_ratings WHERE rating_track=track.row_id) as average_rating FROM track WHERE track.album_id=".$_GET["album_id"]." order by average_rating DESC";
												$result = $conn->query($sql);
												$rank_number=1;
												if ($result->num_rows > 0) {
												    // output data of each row
												    while($row = $result->fetch_assoc()) {

										?>
												<tr><td><?php echo($rank_number); ?></td><td><?php echo($row["track_name"]); ?></td><td><?php echo($row["average_rating"]); ?></td></tr>
										<?php
														$rank_number=$rank_number+1;
											 		}
												}
										?>
											</table>
										</article>
										<article>
											<a href="#" class="image"><img src="images/pic05.jpg" alt="" /></a>
											<h3 class="major">Nisl placerat</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing vehicula id nulla dignissim dapibus ultrices.</p>
											<a href="#" class="special">Learn more</a>
										</article>
									</section>
										<?php
											}
										?>
								</div>
							</div>

					</section>

				<!-- Footer -->
					<?php include("includes/footer.php"); ?>

			</div>
	</body>
</html>