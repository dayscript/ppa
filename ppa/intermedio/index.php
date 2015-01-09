<?
//$path = stripslashes(substr($_SERVER['PATH_TRANSLATED'],0,strpos($_SERVER['PATH_TRANSLATED'],addslashes(str_replace("/","\\",$_SERVER['SCRIPT_NAME']))))) . "\\";
/*require_once("include/db.inc.php");
require_once( 'class/Application.class.php' );

session_start();
if( isset( $_GET['ano'] ) && $_GET['ano'] >= 2003 )$ano = $_GET['ano'];
else $ano = date('Y');
if(isset($_GET['user']))$user = $_GET['user'];
else $user = "dayscript";

if(!session_is_registered("app")){
	$app = new Application("include/config.php");
	$app->connect();
	session_register("app");
	$_SESSION["app"] = $app;
} else {
	if(!isset($app) && isset($_SESSION['app'])){
		$app = $_SESSION['app'];
		$app->connect();
	}	
}
*/
session_start();
$user = $_SESSION['user'];
?>
<table width="40%" align="center" border="1" class="textos">
<tr bgcolor="#FFCC99">
<td align="center">
Tema
</td>
<td align="center">
Año
</td>
<td align="center">
Mes
</td>
<td align="center">
Volumen
</td>
<td align="center">
Número
</td>
</tr>
<?
$sql = "select * from revista where ano = '".$ano."' order by mes";
$sql1 = "select MAX( num ) from revista";
$query = db_query( $sql );
$query1 = db_query( $sql1 );
$num_revistascreate = 0;
$row1 = db_fetch_array( $query1 );
while( $row = db_fetch_array( $query ) ){
   $id_rev[] = $row['id'];
   $mes_rev[] = $row['mes'];
   $num_rev[] = $row['num'];
   $vol_rev[] = $row['vol'];
   $ano_rev[] = $row['ano'];  
   $sql2 = "select po.tema from pagina pa, portada po where pa.revista = '".$row['id']."' and pa.id = po.pagina and pa.numero = -1";
   $query2 = db_query( $sql2 );
   $row2 = db_fetch_array( $query2 );
   $tema_rev[] = $row2['tema'];   
}
$num_max = $row1[0];
$volumen = 2;
if( db_numrows( $query ) < 12 ){
  $num1 = $num_rev[0] - $mes_rev[0]; 
  for( $i = 1; $i < $mes_rev[0] ; $i++ ){
    $num1++;
    $revista = new Revista( false, $ano, $i, $volumen , $num1 );
	$revista->commit();
	?>
	<tr>
	  <td align="center">
	  <a href="intermedio.php?id=<?=$revista->getId()?>">Sin Tema</a>
	  </td>
	  <td align="center">
	  <?=$ano ?>
	  </td>	  
	  <td align="center">
	  <?=$i ?>
	  </td>	  	  
	  <td align="center">
	   <?=$volumen?>
	  </td>	  	  
	  <td align="center">
	  <?=$num1 ?>
	  </td>	  	  	  
	</tr>
	<?
  }

  for( $i = 0 ; $i < count( $id_rev ) ; $i++ ){
	?>
	<tr>
	  <td align="center">
	  <a href="intermedio.php?id=<?=$id_rev[$i]?>"><?=( trim( $tema_rev[$i] )  != "" ) ? ( $tema_rev[$i] ):( "Sin Tema" ) ?></a>
	  </td>
	  <td align="center">
	  <?=( trim( $ano_rev[$i] )  != "" ) ? ( $ano_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  
	  <td align="center">
	  <?=( trim( $mes_rev[$i] )  != "" ) ? ( $mes_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  
	  <td align="center">
	  <?=( trim( $vol_rev[$i] )  != "" ) ? ( $vol_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  
	  <td align="center">
	  <?=( trim( $num_rev[$i] )  != "" ) ? ( $num_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  	  
	</tr>
	<?     
  }

  if( ( $mes_rev[0] + db_numrows( $query ) ) == 0 ){
    $mes_rev[0] = 1;
  }
  for( $i = $mes_rev[0] + db_numrows( $query ) ; $i <= 12; $i++ ){  
    $num_max++;
    $revista = new Revista( false, $ano, $i, 2, $num_max );
//	$revista->_print();
	$revista->commit();	
	?>
	<tr>
	  <td align="center">
       <a href="intermedio.php?id=<?=$revista->getId()?>">Sin Tema</a>
	  </td>
	  <td align="center">
	  <?=$ano ?>
	  </td>	  
	  <td align="center">
	  <?=$i ?>
	  </td>	  	  
	  <td align="center">
	   <?=$volumen?>
	  </td>	  	  
	  <td align="center">
	  <?=$num_max ?>
	  </td>	  	  	  
	</tr>
	<?	
  }
}else{
  for( $i = 0 ; $i < count( $id_rev ) ; $i++ ){
	?>
	<tr>
	  <td align="center">
	  <a href="intermedio.php?id=<?=$id_rev[$i]?>"><?=( trim( $tema_rev[$i] )  != "" ) ? ( $tema_rev[$i] ):( "Sin Tema" ) ?></a>
	  </td>
	  <td align="center">
	  <?=( trim( $ano_rev[$i] )  != "" ) ? ( $ano_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  
	  <td align="center">
	  <?=( trim( $mes_rev[$i] )  != "" ) ? ( $mes_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  
	  <td align="center">
	  <?=( trim( $vol_rev[$i] )  != "" ) ? ( $vol_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  
	  <td align="center">
	  <?=( trim( $num_rev[$i] )  != "" ) ? ( $num_rev[$i] ):( "&nbsp" ) ?>
	  </td>	  	  	  
	</tr>
	<?     
  }
}
?>
</table>
<table width="40%" align="center" >
<tr>
<td align="left">
<? if( ( $ano - 1 ) >= 2003 ) {?>
<a href="intermedio.php?ano=<?=$ano-1;?>">Anterior</a>
<? }?>
</td>
<td align="right">
<a href="intermedio.php?ano=<?=$ano+1;?>">Siguiente</a>
</td>
</tr>
</table>
