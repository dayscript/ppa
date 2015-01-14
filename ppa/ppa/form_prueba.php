<form name="form1" action="channels_prueba.php" method="post" enctype="multipart/form-data">
<table>
      <tr>
        <td><div align="right"><strong>Mes</strong></div></td>
        <td>
          <select name="mes">
            <option value="01">Enero</option>
            <option selected value="02">Febrero</option>
            <option value="03">Marzo</option>
            <option value="04">Abril</option>
            <option value="05">Mayo</option>
            <option value="06">Junio</option>
            <option value="07">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Año</strong></div></td>
        <td>
          <select name="ano">
            <option value="2003">2003</option>
            <option selected value="2004">2004</option>
            <option value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
            <option value="2009">2009</option>
            <option value="2010">2010</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Tipo de archivo</strong></div></td>
        <td>
          <select name="tipo_archivo">
            <option value="1">Tipo 1</option>
            <option value="2">Tipo 2</option>
            <option value="3">Tipo 3</option>
            <option value="4">Tipo 4</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Archivo</strong></div></td>
        <td>
          <input name="archivo" type="file">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="hidden" name="id" value="132">
          <input type="hidden" name="asign_program" value="1">
          <input type="hidden" name="paso" value="2">
          <input type="submit" name="next" value="Siguiente" >
          <br>
          <input type="button" name="send2" value="Regresar" onClick="javascript:window.document.location.href='channels.php'">
        </td>
      </tr>
</table>
</form>