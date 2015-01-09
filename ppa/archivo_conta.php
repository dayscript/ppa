<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Nelson
 * Date: 13/12/12
 * Time: 13:31
 * To change this template use File | Settings | File Templates.
 */

error_reporting (E_ALL);
ini_set ("display_errors", 1);
ini_set( 'date.timezone', 'America/Bogota' );
set_time_limit( 0 );

function fillText( $str, $length, $fillChar = " " )	{
	return sprintf( "%" . $fillChar[0] . $length . "s", $str );
}


function strToMoney( $money )	{
	return number_format ( $money, 2,",","." );
}

$errors = array( );
$lines = array(
	'lines' => array( ),
	'total' => 0,
	'value' => 0,
	'value_error' => 0
);

$file = array( );




$sizes = array( 2,5,2,16,6,5,20,30,20,30,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,6,6,6,6,6,2,2,2,2,9,1,9,9,9,9,4,4,9,9,9,9,9,9,8,9,9,15,9,15,9,9,9,9,6,9,6,9,6,9,6,9,6,9 );

$sizes = array( 2,5,2,16,2,2,1,1,2,3,20,30,20,30,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,6,6,6,6,6,2,2,2,2,9,1,9,9,9,9,7,9,9,9,9,9,9,9,7,9,9,15,9,15,9,9,9,9,7,9,7,9,7,9,7,9,7,9 );
$NLef = array( 0,1,4,5,9,29,35,36,37,38,39,41,42,43,44,46,47,48,49,50,51,52,54,55,57,59,61,62,64,66,68,70,72 );
$NDec = array( 45,53,60,63,65,67,69,71 );
//$sizes = array( 192,10,9,9,9,9,3,170,13,3,13,3,13,31 );

if( isset( $_FILES['ASOFP'] ) && is_uploaded_file( $_FILES['ASOFP']['tmp_name'] ) )	{

	header("Content-type: application/txt");
	header("Content-Disposition: attachment; filename=ArchivoCSV.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$file = file( $_FILES['ASOFP']['tmp_name'] );
	$cont = 0;
	foreach( $file as $nLine => $fileLine )	{
		// Recorrer solo los detalles

		if( (int)substr( $fileLine , 0, 2 ) == 2 )	{
			$pos = 0;
			$line = "";
			$cont ++;
			foreach( $sizes as $size )  {
				$line .= trim( substr( $fileLine, $pos, $size ) ) . ';';
				$pos += $size;
			}
			echo trim( $line, ";" ) . "\r\n";
/*

			foreach( $sizes as $size )  {
				if( $pos == 2 )
					$line .= $cont . ';';
				else if( $pos == 143 )
					$line .= "A" . ';';
				else
					$line .= trim( substr( $fileLine, $pos, $size ) ) . ';';
				$pos += $size;
			}
			echo trim( $line, ";" ) . "\r\n";

			$pos = 0;
			$line = "";
			$cont ++;
			foreach( $sizes as $size )  {
				if( $pos == 2 )
					$line .= $cont . ';';
				else if( $pos == 143 )
					$line .= "C" . ';';
				else
					$line .= trim( substr( $fileLine, $pos, $size ) ) . ';';
				$pos += $size;
			}
			echo trim( $line, ";" ) . "\r\n";*/
		}
	}
	exit( );
}

if( isset( $_FILES['ASOF'] ) && is_uploaded_file( $_FILES['ASOF']['tmp_name'] ) )	{
	$file = file( $_FILES['ASOF']['tmp_name'] );

	header("Content-type: application/txt");
	header("Content-Disposition: attachment; filename=ArchivoTXT.txt");
	header("Pragma: no-cache");
	header("Expires: 0");

	echo "0100001DAYSCRIPT LTDA                                                                                                                                                                                          NI830073139       9N75275813232011-07-08C                                                  14-25 2011-062011-070000000000          00165000084661360100\n";

	foreach( $file as $nLine => $fileLine )	{
		// Recorrer solo los detalles

		if( (int)substr( $fileLine , 0, 2 ) == 2 )	{
			$parts = explode( ";", $fileLine );
			if( count( $parts ) == count( $sizes ) )	{
				for( $c = 0; $c < count( $parts ); $c ++ )  {
					if( in_array( $c, $NLef ) )
						echo fillText( trim( $parts[$c] ), $sizes[$c], "0" );
					else if( in_array( $c, $NDec ) )    {
						$value = '' . number_format( trim( $parts[$c] ), $sizes[$c] - 2, '.', '' );
						echo fillText( trim( $value ), -$sizes[$c] );
					}
					else
						echo fillText( trim( $parts[$c] ), -$sizes[$c] );
				}
			}
			else	{
				$errors[] = "Fila de archivo '" . ( $nLine + 1 ) . "' con largo incorrecto, no se continua con el proceso." . count( $parts ) . " de " . count( $sizes );
				break;
			}
			echo "\n";
		}
	}
	echo "0400001EPS010                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400002EPS002                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400003EPS003                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400004EPS013                0000000000000000000002410019106     00000711422407108306     00012078920000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400005EPS005                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400006EPS026                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400007EPS016                00000000000000000000015287715       0000089266               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400008EPS018                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400009EPS017                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400010EPS014                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400011EPS023                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400012EPS008                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400013EPS012                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400014EPS037                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
0400015EPS009                000000000000000000000               0000000000               00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
050000114-25                 00000000000               0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
";
	exit();
}

?>
<form name="dataForm" action="" method="post" enctype="multipart/form-data">
	<table cellpadding="5" cellspacing="1" align="center" style="font-size:12px; font-family:sans-serif;">
		<tr>
			<th colspan="2">Cargar archivo a convertir</th>
		</tr>
		<tr>
			<td>Selecccione el archivo a cortar:</td>
			<td><input type="file" name="ASOFP" id="ASOFP" value="" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Convertir" /></td>
		</tr>
	</table>
</form>

<form name="dataForm" action="" method="post" enctype="multipart/form-data">
	<table cellpadding="5" cellspacing="1" align="center" style="font-size:12px; font-family:sans-serif;">
		<tr>
			<th colspan="2">Cargar archivo a procesar</th>
		</tr>
		<tr>
			<td>Selecccione el archivo:</td>
			<td><input type="file" name="ASOF" id="ASOF" value="" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="Cargar" /></td>
		</tr>
	</table>
</form>
