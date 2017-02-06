<?php
	session_start();
?>
<!DOCTYPE HTML>
<!--
	Solid State by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>The Set - Log In</title>
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
								<h2>The Set</h2>
								<p>50 Years - 150 Albums - 2 Mandelbros</p>
							</div>
						</header>

						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
									<form id="login" method="post" action="dologin.php">
									<div class="6u$ 12u$(xsmall)">
										<label for="email">Email</label>
										<input type="email" name="email" id="email" value="" />
									</div>
									<div class="6u$ 12u$(xsmall)">
										<label for="password">Password</label>
										<input type="password" name="password" id="password" value="" />
									</div><br>
									<div class="12u$">
										<ul class="actions">
											<input type="hidden" name="prevurl" value="<?php echo($_GET["prevurl"]);?>">
											<li><input type="submit" value="Login" class="special" /></li>
											<li><input type="reset" value="Reset" /></li>
										</ul>
									</div>
									</form>
								</div>
							</div>

					</section>

				<!-- Footer -->
					<?php include("includes/footer.php"); ?>

			</div>
	</body>
</html>