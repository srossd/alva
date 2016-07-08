<?php
if($_GET["password"] == "abc123")
	echo "<div id='changeProblem'><a href='#editProblem' class='fancybox button subtle'>Change Problem</a></div><div style='display:none;'><div id='editProblem' align='center'><textarea rows='4' cols='50' id='newProblem'></textarea><br /><a href='javascript:room.child(\"problem\").set($(\"#newProblem\").val()); $.fancybox.close();' class='button'>Change</a></div></div>";
?>