<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es">
<head>
    <title>Dayscript :: Grilla de programación</title>

    <!-- ELIMINAR -->
    <!--
        En esta zona se deben insertar las hojas de estilos del sitio principal
     -->
    <link href="http://www.inter.com.ve/public/css/main.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="/ppa/jsapps/inter2/js/jquery-1.8.0.min.js"></script>
    <!-- FIN ELIMINAR -->

    <!--[if IE]>
	<link href="http://spinebone.net/ppa/jsapps/inter2/css/reset.css" type="text/css" rel="stylesheet"/>
    <![endif]-->

    <script type="text/javascript" src="http://spinebone.net/ppa/jsapps/inter2/js/GridAppSetup.js"></script>
    <script type="text/javascript">
        ppaImport("filters"); // Agregar los scripts necesarios para los filtros
    </script>
    <!--
        En esta zona se deben insertar las hojas de estilos que va a sobre-escribir los de la guía
     -->
</head>
<body>
<div>
	<div> <!-- CONTENEDOR DE FILTROS -->
        <script type="text/javascript">
            GridApp.setHeadend("Anaco"); // Selecciona la cabecera por defecto
	        GridApp.createFilters();
        </script>
    </div>
    <div> <!-- CONTENEDOR DE GRILLA -->
	    <script type="text/javascript">
		    GridApp.createGrid();
		    GridApp.consultGrid( 0 );
	    </script>
    </div>
</div>
</body>
</html>