<html>
<head>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>
<link href='http://fonts.googleapis.com/css?family=Iceland' rel='stylesheet' type='text/css'>
<link href='stylesheet.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> 
MathJax.Hub.Config({
		extensions: ["tex2jax.js"],
		jax: ["input/TeX","output/HTML-CSS"],
		tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
});
</script>
<script src="admin.js"></script>
</head>
<body id="admin">
<span id="title">
<h1 align="center">Alva Administration</h1>
</span>	
<div id="adminbox">
<p id="adminheader">Question</p>
<textarea rows="7" cols="70" id="question"></textarea>
<p id="adminheader">Answer</p>
<textarea rows="7" cols="70" id="answer"></textarea>
<p id="adminheader">Preview</p>
<p id="preview"></p>
<a href="javascript:submitEdit();" class="button" align="center">Submit</a>
</div>
</body>
</html>