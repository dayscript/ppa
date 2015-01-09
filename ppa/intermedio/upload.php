<form enctype="multipart/form-data" name="f1" action="intermedio.php" method="post">
<input type="hidden" name="date" value="<?=$_GET['date']?>">
<input type="hidden" name="upload" value="1"><br>
<input type="file" name="userfile" usemap="*.txt"><br>
Hora Corte <input type="text" name="hora_inicio" value="00:00"><br>
Canal <input type="text" name="canal" value=""><br>
<input type="submit" value="Enviar"><br>
</form>