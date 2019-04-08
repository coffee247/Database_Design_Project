<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Flexbox Grid System</title>
<link rel = "stylesheet"
   type = "text/css"
   href = "style2.css" />
</head>
<body>
	
<div class="row">
	<div class="col col-span-1"><a title="See page for author [Public domain], via Wikimedia Commons" href="https://commons.wikimedia.org/wiki/File:Maksymilian_Hartlik.jpg"><img width="256" alt="Maksymilian Hartlik" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/Maksymilian_Hartlik.jpg/256px-Maksymilian_Hartlik.jpg"></a></div>
</div>

<div class="row">
	<div class="col col-span-1">1</div>
	<div class="col col-span-2">2</div>
	<div class="col col-span-3">3</div>
	<div class="col col-span-4">4</div>
	<div class="col col-span-2">2</div>
</div>

<div class="row">
	<div class="col col-span-5">5
		<p>Sotto un cespo di rose scarlatte dai al rospo the caldo col latte.</p>
		<p>Sotto un cespo di rose paonazze tocca al rospo lavare le tazze.</p>
	</div>
	<div class="col col-span-3">3</div>
	<div class="col col-span-4">4</div>
</div>
	
<div class="row">
	<div class="col col-span-6">6
		<div class="row nested">
			<div class="col col-span-7">7</div>
			<div class="col col-span-2">2</div>
			<div class="col col-span-3">3</div>
		</div>
	</div>
	<div class="col col-span-6">6
		<div class="row nested wide-fit">
			<div class="col col-span-7">7</div>
			<div class="col col-span-2">2</div>
			<div class="col col-span-3">3</div>
		</div>
	</div>
</div>

<div class="row center">
	<div class="col col-span-6">6
		<div class="row nested wide-fit">
			<div class="col col-span-7">7</div>
			<div class="col col-span-2">2</div>
			<div class="col col-span-3">3</div>
		</div>
	</div>
</div>

<div class="row center">
	<div class="col col-span-3">3</div>
	<div class="col col-span-3">3</div>
</div>

<div class="row">
	<div class="col col-span-7">7</div>
	<div class="col col-span-2">2</div>
	<div class="col col-span-3">3</div>
</div>

<div class="row">
	<div class="col col-span-8">8</div>
	<div class="col col-span-1">1</div>
	<div class="col col-span-3">3</div>
</div>

<div class="row">
	<div class="col col-span-9">9</div>
	<div class="col col-span-1">1</div>
	<div class="col col-span-2">2</div>
</div>

<div class="row">
	<div class="col col-span-10">10</div>
	<div class="col col-span-1">1</div>
	<div class="col col-span-1">1</div>
</div>

<div class="row">
	<div class="col col-span-11">11</div>
	<div class="col col-span-1">1</div>
</div>

<div class="row">
	<div class="col col-span-12">12</div>
</div>

<div class="row center">
	<div class="col col-span-6">6</div>
</div>

<div class="row center">
	<div class="col col-span-3">3</div>
	<div class="col col-span-3">3</div>
</div>

<div class="row">
	<div class="col fixed-width">
		<p>Fixed width column</p>
	</div>
	<div class="col">
		<div class="row nested">
			<div class="col col-span-7">7</div>
			<div class="col col-span-2">2</div>
			<div class="col col-span-3">3</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col fixed-width">
		<p>Fixed width column</p>
	</div>
	<div class="col">
		<div class="row nested wide-fit">
			<div class="col col-span-7">7</div>
			<div class="col col-span-2">2</div>
			<div class="col col-span-3">3</div>
		</div>
	</div>
</div>
	
</body>
</html>