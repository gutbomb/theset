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
	$sql = "SELECT id_fix, album_blurb, source_name, album_name, year_status, artist_name, album_genre, year FROM jason_albums JOIN jason_artists ON jason_artists.artist_id = jason_albums.artist_id JOIN jason_years ON jason_years.year_id = jason_albums.album_year JOIN jason_sources ON jason_sources.source_id = jason_albums.album_source WHERE album_id=".$_GET["album_id"];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$albumtitle=$row["album_name"];
			$albumartist=$row["artist_name"];
	    	$albumyear=$row["year"];
	    	$albumgenre=$row["album_genre"];
	    	$albumsource=$row["source_name"];
	    	$albumblurb=$row["album_blurb"];
	    	$id_fix=$row["id_fix"];
	    	if($row["year_status"]=="active")
	    	{
	    		$hide_ratings=0;
	    	}
	    	else
	    	{
	    		$hide_ratings=1;
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
								<h2><?php echo($albumtitle); ?> by <?php echo($albumartist); ?></h2>
								<p><?php echo($albumgenre); ?> album released in <?php echo($albumyear); ?> | Picked by <?php echo($albumsource); ?></p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<a id="tracklist"></a><h3 class="major">Track Listing</h3>
										<table>
											<tr>
												<td>#</td>
												<td>Name</td>
												<td>Rating</td>
												<td></td>
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
															$conn2 = new mysqli($servername, $username, $password, $dbname);
															// Check connection
															if ($conn2->connect_error) {
															    die("Connection failed: " . $conn->connect_error);
															}
															$ratingsql = "SELECT rating_id, rating_score FROM jason_ratings WHERE rating_track=".$row["row_id"]." AND rating_source=".$user_id;
															$ratingresult = $conn2->query($ratingsql);
															$dbmethod="insert";
															$rating_id=NULL;
															$selected10=NULL;
													    	$selected15=NULL;
													    	$selected20=NULL;
													    	$selected25=NULL;
													    	$selected30=NULL;
													    	$selected35=NULL;
													    	$selected40=NULL;
													    	$selected45=NULL;
													    	$selected50=NULL;
													    	$selectedstandard=NULL;
													    	$selectedlegendary=NULL;
													    	$selectedpower=NULL;
															if ($ratingresult->num_rows > 0) {
															    $dbmethod="update";
															    // output data of each row
															    while($ratingrow = $ratingresult->fetch_assoc()) {
															    	$rating_id=$ratingrow["rating_id"];
															    	switch ($ratingrow["rating_score"]){
																		case "1.0":
																			$selected10=" selected";
																			break;
																		case "1.5":
																			$selected15=" selected";
																			break;
																		case "2.0":
																			$selected20=" selected";
																			break;
																		case "2.5":
																			$selected25=" selected";
																			break;
																		case "3.0":
																			$selected30=" selected";
																			break;
																		case "3.5":
																			$selected35=" selected";
																			break;
																		case "4.0":
																			$selected40=" selected";
																			break;
																		case "4.5":
																			$selected45=" selected";
																			break;
																		case "5.0":
																			$selected50=" selected";
																			break;
																		default:
																			$selected10=" selected";
																	}
																}
															}
											?>
												<script type="text/javascript">
													$(document).ready(function () {
													    $('#track_<?php echo($row["row_id"]); ?>').on('submit', function(e) {
													        e.preventDefault();
													        $.ajax({
													            url : $(this).attr('action') || window.location.pathname,
													            type: "POST",
													            data: $(this).serialize(),
													            success: function (data) {
													                $("#form_output").html(data);
													                $('#submit_<?php echo($row["row_id"]); ?>').removeClass('special');
													                $('#submit_<?php echo($row["row_id"]); ?>').val("saved");
													                if($('#method_<?php echo($row["row_id"]); ?>').val()=="insert")
													                {
													                	$('#method_<?php echo($row["row_id"]); ?>').val("update");
													                	$('#rating_id_<?php echo($row["row_id"]); ?>').val(data);
													                	//alert(data);
													                }

													            },
													            error: function (jXHR, textStatus, errorThrown) {
													                alert(errorThrown);
													            }
													        });
													    });
													    $('#ratingscore_<?php echo($row["row_id"]); ?>').on('change', function(e) {
													        e.preventDefault();
													        $.ajax({
													            url : $('#track_<?php echo($row["row_id"]); ?>').attr('action') || window.location.pathname,
													            type: "POST",
													            data: $('#track_<?php echo($row["row_id"]); ?>').serialize(),
													            success: function (data) {
													                $("#form_output").html(data);
													                $('#submit_<?php echo($row["row_id"]); ?>').removeClass('special');
													                $('#submit_<?php echo($row["row_id"]); ?>').val("saved");
													                if($('#method_<?php echo($row["row_id"]); ?>').val()=="insert")
													                {
													                	$('#method_<?php echo($row["row_id"]); ?>').val("update");
													                	$('#rating_id_<?php echo($row["row_id"]); ?>').val(data);
													                	//alert(data);
													                }

													            },
													            error: function (jXHR, textStatus, errorThrown) {
													                alert(errorThrown);
													            }
													        });
													    });
													    $('#ratingcategory_<?php echo($row["row_id"]); ?>').on('change', function(e) {
													        e.preventDefault();
													        $.ajax({
													            url : $('#track_<?php echo($row["row_id"]); ?>').attr('action') || window.location.pathname,
													            type: "POST",
													            data: $('#track_<?php echo($row["row_id"]); ?>').serialize(),
													            success: function (data) {
													                $("#form_output").html(data);
													                $('#submit_<?php echo($row["row_id"]); ?>').removeClass('special');
													                $('#submit_<?php echo($row["row_id"]); ?>').val("saved");
													                if($('#method_<?php echo($row["row_id"]); ?>').val()=="insert")
													                {
													                	$('#method_<?php echo($row["row_id"]); ?>').val("update");
													                	$('#rating_id_<?php echo($row["row_id"]); ?>').val(data);
													                	//alert(data);
													                }

													            },
													            error: function (jXHR, textStatus, errorThrown) {
													                alert(errorThrown);
													            }
													        });
													    });
													});
												</script>
												<td style="width:6em"><form method="post" action="doratings.php" id="track_<?php echo($row["row_id"]); ?>">
													<div class="select-wrapper">
														<select name="ratingscore" id="ratingscore_<?php echo($row["row_id"]); ?>" onfocus="$('#submit_<?php echo($row["row_id"]); ?>').addClass('special');$('#submit_<?php echo($row["row_id"]); ?>').val('Save');">
															<option value="1.0"<?php echo($selected10);?>>1.0</option>
															<option value="1.5"<?php echo($selected15);?>>1.5</option>
															<option value="2.0"<?php echo($selected20);?>>2.0</option>
															<option value="2.5"<?php echo($selected25);?>>2.5</option>
															<option value="3.0"<?php echo($selected30);?>>3.0</option>
															<option value="3.5"<?php echo($selected35);?>>3.5</option>
															<option value="4.0"<?php echo($selected40);?>>4.0</option>
															<option value="4.5"<?php echo($selected45);?>>4.5</option>
															<option value="5.0"<?php echo($selected50);?>>5.0</option>
														</select>
													</div>
												</td>
												<td><input type="hidden" name="track_id" value="<?php echo($row["row_id"]); ?>"><input type="hidden" name="album_id" value="<?php echo($_GET["album_id"]); ?>"><input type="hidden" name="rating_id" value="<?php echo($rating_id); ?>" id="rating_id_<?php echo($row["row_id"]); ?>"><input name="method" type="hidden" value="<?php echo($dbmethod); ?>" id="method_<?php echo($row["row_id"]); ?>"><input id="submit_<?php echo($row["row_id"]); ?>" type="submit" value="Save" class="special" /></form></td>
											<?php
															$conn2->close();
															$tracknumber=$tracknumber+1;
													    }
													    else
													    {
											?>
											<td></td>
											<?php
													    }
													}
													$conn->close();
												}
											?>
										</table>
											<?php
												if($hide_ratings==0)
												{
											?>
										<h3 class="major">Your Review</h3>
										<?php	
												$conn = new mysqli($servername, $username, $password, $dbname);
												// Check connection
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "SELECT review_text, review_id FROM jason_reviews WHERE review_source='".$user_id."' AND review_album=".$_GET["album_id"];
												$result = $conn->query($sql);
												if ($result->num_rows > 0) {
												    // output data of each row
												    while($row = $result->fetch_assoc()) {
												    	$review_text=$row["review_text"];
												    	$review_id=$row["review_id"];
												    	$reviewmethod="update";
												    	$album_id=$_GET["album_id"];
												    }
												}
												else
												{
													$review_text="";
											    	$review_id="";
											    	$reviewmethod="insert";
											    	$album_id=$_GET["album_id"];
												}
										?>
										<script type="text/javascript">
											$(document).ready(function () {
											    $('#reviewform').on('submit', function(e) {
											        e.preventDefault();
											        $.ajax({
											            url : $(this).attr('action') || window.location.pathname,
											            type: "POST",
											            data: $(this).serialize(),
											            success: function (data) {
											                $("#form_output").html(data);
											                $('#submitreview').removeClass('special');
											                $('#submitreview').val('saved');
											                if($('#reviewmethod').val()=='insert')
											                {
											                	$('#review_id').val(data);
											                	$('#reviewmethod').val('update');
											                }
											            },
											            error: function (jXHR, textStatus, errorThrown) {
											                alert(errorThrown);
											            }
											        });
											    });
											});
										</script>
										<form action="doreview.php" method="post" id="reviewform">
											<div class="12u$">
												<textarea name="review_text" id="review_text" rows="6" onfocus="$('#submitreview').addClass('special');$('#submitreview').val('Save Review');"><?php echo($review_text);?></textarea>
											</div>
											<div class="12u$">
												<ul class="actions">
													<input type="hidden" id="review_id"; name="review_id" value="<?php echo($review_id); ?>">
													<input type="hidden" name="album_id" value="<?php echo($album_id); ?>">
													<input type="hidden" name="method" id="reviewmethod" value="<?php echo($reviewmethod); ?>">
													<li><input id="submitreview" type="submit" value="Save Review" class="special" /></li>
													<li><input type="reset" value="Reset" /></li>
												</ul>
											</div>
										</form>
										<?php
											$conn->close();
										}
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