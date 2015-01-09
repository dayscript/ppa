<?
if(!(isset($_GET['hora']) && $_GET['hora'])){
  $_GET['hora'] = date("H").((date("i")<29)? "00":"30");
}
if( !strstr( $_GET['hora'], ":"  ) ){
  $_GET['hora'] = date("H:i", strtotime( $_GET['hora'] ) );
} 
if(!(isset($_GET['dia']) && $_GET['dia'])){
  $_GET['dia'] = date("d");
}
if(!(isset($_GET['mes']) && $_GET['mes'])){
  $_GET['mes'] = date("m");
}
if(!(isset($_GET['ciudad']) && $_GET['ciudad'])){
  $_GET['ciudad'] = 'barquisimeto';
}
if(!(isset($_GET['fecha']) && $_GET['fecha'])){
  $_GET['fecha'] = date("Y")."-".$_GET['mes']."-".$_GET['dia'];
}
if($_GET['busqueda']=="avanzada"){
  include("http://intercable.dayscript.com/programacion/avanzada.php");
}
elseif($_GET['busqueda']=="canal" || isset($_GET['canal'])){
  include("http://intercable.dayscript.com/programacion/canal.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['canal']) && $_GET['canal'])? "&canal=".$_GET['canal']:""));
}elseif($_GET['busqueda']=="programa" || isset($_GET['programa'])){
  include("http://intercable.dayscript.com/programacion/programa.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['programa']) && $_GET['programa'])? "&programa=".$_GET['programa']:""));
}
elseif($_GET['busqueda']=="actor" || isset($_GET['actor'])){
  include("http://intercable.dayscript.com/programacion/actor.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['actor']) && $_GET['actor'])? "&actor=".$_GET['actor']:""));
}
elseif($_GET['busqueda']=="director" || isset($_GET['director'])){
  include("http://intercable.dayscript.com/programacion/director.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['director']) && $_GET['director'])? "&director=".$_GET['director']:""));
}
elseif($_GET['busqueda']=="genero" || isset($_GET['genero'])){
  include("http://intercable.dayscript.com/programacion/genero.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['genero']) && $_GET['genero'])? "&genero=".$_GET['genero']:""));
}
elseif($_GET['busqueda']=="hora" && (isset($_GET['dia']) && isset($_GET['hora']))){
  include("http://intercable.dayscript.com/programacion/hora.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad'].((isset($_GET['hora']) && $_GET['hora'])? "&hora=".$_GET['hora']:"").((isset($_GET['dia']) && $_GET['dia'])? "&dia=".$_GET['dia']:""));
}
elseif( isset( $_GET['sinopsis'] ) && isset( $_GET['canal_sinop'] )){
  include("http://intercable.dayscript.com/programacion/sinopsis.php?sinopsis=".$_GET['sinopsis']."&hora_sinop=".$_GET['hora']."&canal_sinop=".$_GET['canal_sinop']);
}
else{
  include("http://intercable.dayscript.com/programacion/index.php?hora=".$_GET['hora']."&mes=".$_GET['mes']."&dia=".$_GET['dia']."&fecha=".$_GET['fecha']."&ciudad=".$_GET['ciudad']);
}
?>
