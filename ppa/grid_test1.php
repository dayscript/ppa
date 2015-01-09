<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
<title>Dayscript :: Grilla de programación</title>
<link href="http://200.71.33.251/webgrid/css/filters.css" type="text/css" rel="stylesheet"/>
<link href="http://200.71.33.251/webgrid/css/grid.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="http://200.71.33.251/webgrid/js/GridAppSetup.js"></script>
<script type="text/javascript">ppaImport("filters");</script>
<script type="text/javascript">
	GridApp.lockHeadend();
	GridApp.lockGender();
	GridApp.lockChannel();
	GridApp.setHeadend("bogotá");
	GridApp.setChannel("<?=$_POST['channel']?>");
	GridApp.setGender("<?=$_POST['gender']==""?"Nacionales":$_POST['gender']?>");
	GridApp.setStartDate("<?=$_POST['ppa_startDate']?>");
	GridApp.setEndDate("<?=$_POST['ppa_endDate']?>");
	GridApp.setTitle("<?=$_POST['ppa_title']?>");
	GridApp.setActor("<?=$_POST['ppa_actor']?>");
</script>
</head>
<body>
<div style="width:681px">
<div style="background-image: url(images/filter_bg.gif); background-repeat: no-repeat;width:680px;height:122px;"><script type="text/javascript">GridApp.createFilters();</script></div>
<div style="background-image: url( images/guide_title.gif );height:50px;margin-top:10px"></div>
<div><script type="text/javascript">GridApp.createGrid();</script></div>
<div style="background-image: url( images/guide_foot.gif );height:39px"></div>
</div>
</body>
</html>