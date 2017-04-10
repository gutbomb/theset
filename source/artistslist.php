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
								<h2>Artists List</h2>
								<p>All the artists picked for this project</p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<?php
										$sql = "SELECT artist_name, artist_id FROM jason_artists ORDER BY SUBSTRING(UPPER(artist_name), IF(artist_name LIKE 'The %', 5, 1))";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
										    // output data of each row
										    while($row = $result->fetch_assoc()) {
									?>
									<p><b><a href="viewartist.php?artist_id=<?php echo $row["artist_id"]; ?>"><?php echo($row["artist_name"]); ?></a></b></p>
									<?php			    	
										    }
									    }
										$conn->close();
									?>
								</div>
							</div>

					</section>

				<!-- Footer -->
					<?php include("includes/footer.php"); ?>

			</div>
	</body>
</html>