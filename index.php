<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Day Rating</title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="main.css">
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script src="app.js"></script>
</head>
<body>
	<label for="slider">Rating of the day:</label>
	<input type="range" name="slider" id="slider" value="5" min="1" max="10" />
	<textarea name="comment" id="comment" placeholder="Your accomplishment of the day" autofocus></textarea>
	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>rating</th>
				<th>comment</th>
				<th>date</th>
			</tr>
		</thead>
		<tbody id="tableData">
		</tbody>
	</table>
</body>
</html>