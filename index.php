<!-- testing -->
<?php
	$weather = $city = $error = "";
	
	if (array_key_exists('city', $_GET)) {		
		$city = str_replace(' ', '', $_GET['city']);
		if ($city == "") {
			$error = "Please enter a city before submitting";
		} else {
			$locationContent = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . $city . "&key=MY_API_KEY");
			$locationArray = json_decode($locationContent, true);
			//print_r($locationArray);
			if ($locationArray['status'] == "ZERO_RESULTS") {
				$error = "Could not find city - please try again.";
			} else {
				$lat = $locationArray['results'][0]['geometry']['location']['lat'];
				$lon = $locationArray['results'][0]['geometry']['location']['lng'];
				$place = $locationArray['results'][0]['address_components'][0]['long_name'];
				//echo $lat ." ". $lng;
				$weatherContent = file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $lon . "&appid=MY_API_KEY");
				$weatherArray = json_decode($weatherContent, true);
				//print_r($weatherArray);
				if ($weatherArray['cod'] == 200) {
					$weather = "The weather in ". $place ." is currently '".$weatherArray['weather'][0]['description']."'. ";
					$tempK = $weatherArray['main']['temp'];
					$tempC = $tempK - 273.15;
					$windSpeed = $weatherArray['wind']['speed'];
					$weather .= " The temperature is " . intval($tempC) . "&deg;C and the wind speed is " . $windSpeed . " m/s. ";
					$wind = $weatherArray['wind']['speed'];
				} else {
					$error = "Could not find city - please try again.";
				}
			}
		}
	}

		
?>

<!DOCTYPE html>
<html lang="en">

  <head>
		<!-- Required meta tags always come first -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
	
		<title>Weather scanner</title>
		<link REL="SHORTCUT ICON" HREF="weather.ico">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
	
		<style type="text/css">
			html {
				background: url(weather-bg.jpg) no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
			body {
				background: none;
				color: white;
			}
			.container {
				text-align: center;
				margin-top: 50px;
				color: #D7AD88;
				width: 450px;
				
			}
			#city {
				text-transform: uppercase;
				text-align: center;
				margin: 20px 0 20px 0;
			}
			#weather {
				margin-top: 20px;
			}
			strong {
				text-transform: uppercase;
			}
		</style>	
	</head>

	<body>
		<div class="container">
			<h1>What's the weather?</h1>
			<form>
				<div class="form-group">
				  <label for="city">Enter the name of a city</label>
				  <input name="city" type="text" class="form-control" id="city" placeholder="Eg. ENSCHEDE">
				</div>
				
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			<div id="weather">
				<?php
					if ($weather) {
						echo '<div class="alert alert-success" role="alert">' . $weather . '</div>';		
					} else if ($error) {
						echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
					} 
				?>
			</div>
		</div>    

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
	</body>
</html>