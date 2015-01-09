<form enctype="multipart/form-data" name="f1" action="intermedio.php" method="post">
<input type="hidden" name="download" value="1"><br>
Desplazamiento <input type="text" name="desplazamiento" value="0"><br>
Canal Origen<input type="text" name="canal" value=""><br>
Canal Destino<input type="text" name="canal2" value=""><br>
<input type="hidden" name="fecha" value="<?=$_GET['date']?>">
<input type="hidden" name="fecha2" value="<?=$_GET['date2']?>">
<input type="submit" value="Enviar"><br>
</form>