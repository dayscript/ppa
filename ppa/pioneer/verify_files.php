<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ndaza
 * Date: 9/10/12
 * Time: 11:20 AM
 * To change this template use File | Settings | File Templates.
 */

if( !isset( $argv[1] ) )
	die( "Se debe especificar el NOMBRE la cabecera ( verify_files.php NOMBRE )\n\n");
if( !file_exists( "properties/" . $argv[1] .".properties" ) )
	die( "No se encuentra archivo de configuración para la cabecera ( properties/NOMBRE.properties )\n\n");

$file = null;

switch( $argv[1] )  {
	case "intercable":
		$file = "files/pi.zip";
		break;
	case "tvcable":
		$file = "files/tc.zip";
		break;
}

if( !$file )    {
	die( "No se encuentra archivo " . $argv[1] . " \n\n");
}

$error = array( );
if( !file_exists( $file ) ) {
	$error[] = "El archivo no se ha generado el día de hoy o no existe.";
}
else    {
	if( filemtime( $file ) < time( ) - 60 * 60 * 2 ) {
		$error[] = "El archivo no se ha generado en las últimas 2 horas: " . date( "Y-m-d H:i:s" ,filemtime( $file ) );
	}

	if( filesize( $file ) < 600 * 1024 ) {
		$error[] = "El archivo generado no tiene el tamaño esperado: " . ceil( filesize( $file ) / 1024 ) . "Kb";
	}
}

if( !empty( $error ) )  {
	$body = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <style type="text/css">

        @media only screen and (max-device-width: 480px) {
            table[class=outer] .global-width-670-to-320 {
                width: 320px !important;
            }
            table[class=outer] .global-width-520-to-320 {
                width: 320px !important;
            }
            table[class=outer] .global-width-500-to-300 {
                width: 300px !important;
            }
            table[class=outer] .global-separator-padding {
                height: 8px !important;
            }
            table[class=outer] .global-shrinking-to-0 {
                height: 0px !important;
            }
            table[class=outer] .global-shrinking-to-10 {
                height: 10px !important;
            }
            table[class=outer] .global-h1 {
                font-size: 14px !important;
            }

            table[class=outer] .cut {
                width: 0 !important;
                padding: 0 !important;
            }
            table[class=outer] .vcut {
                height: 0 !important;
                padding: 0 !important;
            }
            table[class=outer] img.cut {
                display: none !important;
                width: 0 !important;
                height: 0 !important;
            }
            table[class=outer] .cut span, table[class=outer] .cut a {
                display: none !important;
            }
            table[class=outer] .frame {
                width: 320px !important;
                border-left: 0px !important;
                border-right: 0px !important;
            }
            table[class=outer] .main_header.media_header {
                width: 300px !important;
                height: 55px !important;
            }
            table[class=outer] .header_left {
                height: 55px !important;
            }
            table[class=outer] .logo_header {
                height: 62px !important;
            }
            table[class=outer] .main_name {
                font-size: 12px !important;
            }
            table[class=outer] .subtitle {
                font-size: 12px !important;
            }
            table[class=outer] .media_main {
                width: 300px !important;
            }
            table[class=outer] .media_main .intro {
                width: 260px !important;
                font-size: 14px !important;
            }
            table[class=outer] .media_main .intro2 {
                font-size: 14px !important;
            }
            table[class=outer] .media_main .suggestions {
                font-size: 14px !important;
            }
            table[class=outer] .media_more {
                width: 300px !important;
                border-radius: 0 !important;
                background-color: #ffffff !important;
                border-top: 1px solid #e8e8e8 !important;
            }
            table[class=outer] .media_button {
                width: 300px !important;
                background-color: #209ae4 !important;
                padding-top: 3px !important;
                padding-bottom: 3px !important;
            }
            table[class=outer] .media_footer {
                font-size: 11px !important;
                line-height: 14px !important;
                padding: 0 10px !important;
            }
            table[class=outer] .address {
                font-size: 11px !important;
                line-height: 14px !important;
                display: block !important;
            }
            table[class=outer] td[class=footer-padding-top] {
                height: 20px !important;
            }
            table[class=outer] td[class=footer-padding-bottom] {
                height: 17px !important;
            }
            table[class=outer] .media_footer br {
                display: none !important;
            }
            table[class=outer] .spacer.ios {
                display: none !important;
            }
            table[class=outer] .reset {
                display: block !important;
                padding-bottom: 4px !important;
            }
            table[class=outer] .media_logo_div {
                display: block !important;
                position: absolute !important;
                left: 240px !important;
                top: 10px !important;
                background-image: url(\'http://spinebone.net/ppa/ppa/channel_logos/default.jpg\') !important;
background-size: 100% 100% !important;
width: 57px !important;
height: 57px !important;
}
table[class=employee-only-padding-top-bottom] {
width: 310px !important;
}
td[class=employee-only-padding-left-right] {
width: 4px !important;
}
table[class=employee-only] {
width: 310px !important;
}
td[class=header_padding] {
height: 55px !important;
}
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
table[class=outer] .media_button {
padding-top: 3px !important;
padding-bottom: 3px !important;
background-color: #209ae4 !important;
}
table[class=outer] .media_button td.cut {
width: 0 !important;
padding: 0 !important;
}
table[class=outer] .media_button {
padding-left: 10px !important;
padding-right: 10px !important;
}
}

</style>
</head>
<body style="margin: 0; padding: 0; background: #ffffff;-webkit-text-size-adjust: 100%;text-rendering: optimizelegibility;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="outer" style="position: relative; background-color: #dddddd;">
<tbody>
    <tr>
        <td>
            <table class="outer frame" align="center" cellpadding="0" cellspacing="0" border="0" width="670" style="border: 0px; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #ffffff;position: relative; background-color: #dddddd;">
<tbody>
            <tr>
                <td>
                    <a href="http://spinebone.net/ppa/" style="border: none; color: #0379A5; text-decoration: none;"><span class="media_logo_div"></span></a>
                    <table cellpadding="0" cellspacing="0" border="0" width="670" class="header frame" style="background-color: #f2f2f2; table-layout: fixed;">
<tbody>
                    <tr>
                        <td class="header_left cut" style="width: 19px; height: 77px;">   </td>
                        <td height="128" width="128" valign="top" rowspan="2" class="logo_header cut" style="background-color: #ffffff; line-height: 100%;"><a href="http://spinebone.net/ppa/" style="border: none; color: #0379A5; text-decoration: none;"><img class="logo cut" src="http://spinebone.net/ppa/ppa/channel_logos/default.jpg" width="128" height="128" style="border: 0; background-color: #fff; line-height: 100%;border: 0;" /></a></td>
                        <td class="cut" width="9">   </td>
                        <td width="10" height="77" class="header_padding">   </td>
                        <td width="458" height="77" class="main_header media_header" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: #333333;">
<table border="0" cellpadding="0" cellspacing="0">
<tbody>
                            <tr>
                                <td class="main_name" style="font-size: 14px; font-weight: bold; color: #000000;">PPA, </td>
                            </tr>
                            <tr>
                                <td class="subtitle" style="font-size: 14px; color: #666666;">&nbsp;&nbsp;&nbsp;&nbsp;Se ha comunicado</td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td width="10" height="77" class="header_padding">   </td>
                    </tr>
                    <tr>
                        <td class="main header_drop cut" style="border-top: 1px solid #dddddd;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: #ffffff;"> </td>
                        <td class="main header_drop cut" style="border-top: 1px solid #dddddd;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: #ffffff;"> </td>
                        <td class="main header_drop cut" height="17" colspan="4" style="border-top: 1px solid #dddddd;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: #ffffff;"> </td>
                    </tr>
                    </tbody>
                    </table>
                </td>
            </tr>
            <tr>
            <td style="background: #ffffff;">
<style type="text/css">
@media only screen and (max-device-width: 480px) {
	table[class="main"] {
		width: 320px !important;
	}

	table[class="story-padding padding"] {
		width: 320px !important;
	}

	table[class="story-padding padding"] td {
		height: 15px !important;
	}

	table[class="story"] {
		width: 300px !important;
	}

	hr[class="story-separator"] {
		width: 320px !important;
	}

	hr[class="separator"] {
		width: 320px !important;
	}

	td[class="snippet-padding padding"] {
		height: 7px !important;
	}

	td[class="image-padding padding"] {
		width: 15px !important;
	}

	tr[class="title-ribbon-padding padding"] {
		height: 1px !important;
	}

	tr[class="title-padding padding"] {
		height: 10px !important;
	}

	img[class="story-image"] {
		max-width: 90px !important;
	}

	td[id="story-image-wrapper-2"] {
		width: 90px !important;
	}

	td[id="story-image-wrapper-4"] {
		width: 90px !important;
	}

	td[id="story-image-wrapper-5"] {
		width: 90px !important;
	}

	table[class="heading"] {
		width: 300px !important;
		font-size: 14px !important;
		line-height: 14px !important;
	}

	a[class="story-title"] span {
		font-size: 14px !important;
		line-height: 18px !important;
	}

	span[class="story-snippet"] {
		font-size: 14px !important;
		line-height: 16px !important;
	}

	table[class="action"] {
		width: 300px !important;
		border-radius: 0 !important;
		background-color: #ffffff !important;
		border-top: 1px solid #e8e8e8 !important;
	}

	table[class="action-wrapper"] {
		width: 320px !important;
	}

	table[class="action-padding padding"] {
		display: none !important;
	}

	table[class="promo"] {
		width: 300px !important;
	}

	table[class="promo"] span.break {
		display: block;
	}
}
		</style>
                <table width="670" border="0" cellpadding="0" cellspacing="0" class="main frame" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: #ffffff;">
	                <tbody>
	                <tr>
		                <td>
			                <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="heading" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 18px; line-height: 18px; font-weight: bold; color: #333;">
				                <tbody>
				                <tr height="14" class="title-padding padding" style="background-color: white;"></tr>
				                <tr>
					                <td>Mensaje ' . date ( "Y-m-d H:i:s" ) . '</td>
					                <td align="right" valign="middle" style="font-size: 12px; font-weight:normal; color: #bbb; width: 50px;">20 Aug</td>
				                </tr>
				                <tr height="14" class="title-padding padding" style="background-color: white;"></tr>
				                </tbody>
			                </table>
			                <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" class="story" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; background-color: white; table-layout: fixed; overflow: hidden;">
				                <tbody>
				                <tr valign="top">
					                <td><span class="story-snippet" style="color: #555; font-size: 14px; line-height: 18px;">' . implode( "<br />", $error ) . '</span></td>
				                </tr>
				                <tr valign="top">
					                <td height="10" class="snippet-padding padding" style="background-color: white;"></td>
				                </tr>
				                </tbody>
			                </table>
			                <hr width="520" height="1" class="story-separator" style="border: 0; margin: 0 auto; color: #e8e8e8; background-color: #e8e8e8; height: 1px;" />
			                <table cellpadding="0" cellspacing="0" border="0" width="670" class="action-wrapper" style="background-color: white;">
				                <tbody>
				                <tr>
					                <td>
						                <table cellpadding="0" cellspacing="0" width="520" align="center" class="action" style="border-radius: 5px; padding: 10px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: #333333;">
							                <tbody>
							                <tr>
								                <td class="cut"><span class="action_tip" style="font-size: 14px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; color: #333; text-shadow: 0 1px 0 #fff;"><b>Verificador:</b> Archivos PPA para <b>' . $argv[1] . '</b>.</span></td>
								                <td align="right">
									                <style type="text/css">
										                @media only screen and (max-device-width: 480px) {
											                table[class=big-button-template] {
												                width: 300px !important;
											                }

											                tr[class=big-button-template-vertical-padding] {
												                height: 3px !important;
											                }
										                }

										                @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
											                td[class=big-button-template] {
												                padding-left: 10px !important;
												                padding-right: 10px !important;
											                }
										                }
									                </style>
									                <table cellpadding="0" cellspacing="0" border="0" class="big-button-template" style="background-color: #FFF; border-radius: 5px; border: 1px; height: 28px; word-wrap: break-word; text-align: center;background-image: -webkit-linear-gradient(top,white,#E6E6E6);background-image: -o-linear-gradient(top,white,#E6E6E6);background-image: linear-gradient(to bottom,white,#E6E6E6);background-image: -moz-linear-gradient(top,white,#E6E6E6);background-repeat: repeat-x;border: 1px solid #BBB;">
										                <tbody>
										                <tr>
											                <td align="center" style="padding-left: 10px; padding-right: 10px;">
												                <table cellpadding="0" cellspacing="0" border="0" class="big-button-template-inner">
													                <tbody>
													                <tr>
														                <td height="28" class="big-button-template-font" style="color: white; font-size: 13px; font-weight: bold; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; text-align: center;padding-left: 10px; padding-right: 10px;padding-left: 0px; padding-right: 0px;"><a href="http://spinebone.net/ppa/" style="border: none; color: #0379A5; text-decoration: none;color: #333;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);"> PPA </a></td>
													                </tr>
													                </tbody>
												                </table>
											                </td>
										                </tr>
										                </tbody>
									                </table>
								                </td>
							                </tr>
							                </tbody>
						                </table>
					                </td>
				                </tr>
				                <tr>
					                <td class="footer-padding-bottom" height="15"></td>
				                </tr>
				                </tbody>
			                </table>
		                </td>
	                </tr>
	                <tr>
		                <td>
			                <table bgcolor="#eeeeee" border="0" cellpadding="0" cellspacing="0" width="670" class="frame footer" style="background-color: #eeeeee; background-image: url(https://twitter.com/images/email/t1/shadow_bottom.jpg); background-position: top; background-repeat: repeat-x; border-top-color: #dddddd; border-top-style: solid; border-top-width: 1px;">
				                <tbody>
				                <tr>
					                <td colspan="4" height="18" class="footer-padding-top"></td>
				                </tr>
				                <tr>
					                <td class="col cut" style="width: 85px;"></td>
					                <td class="footer_body media_footer" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 17px; color: #777; text-shadow: 0 1px 0 #ffffff;">
						                <div>
							                ¿Visitar PPA?
							                <a class="reset" href="http://spinebone.net/ppa/#about" style="border: none; color: #0379A5; text-decoration: none;">Ir al Admin.</a>
						                </div>
						                <div>
							                <a href="#" class="address" style="border: none; color: #0379A5; text-decoration: none;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; text-decoration: none; font-size: 11px; line-height: 17px; color: #999999; text-shadow: 0 1px 0 #ffffff;">PPA, Dayscript SAS. <span class="nobreak" style="white-space: nowrap;">Bogotá, Colombia</span></a>
						                </div>
					                </td>
					                <td class="col cut" style="width: 85px;"></td>
				                </tr>
				                <tr>
					                <td colspan="3" class="footer-padding-bottom" height="15"></td>
				                </tr>
				                </tbody>
			                </table>
		                </td>
	                </tr>
	                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
    </td>
</tr>
</table>
</body>
</html>';


	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";

	// Additional headers
	$headers .= 'To: Nelson Daza <ndaza@dayscript.com>, David Amaris <damaris@dayscript.com>' . "\r\n";
	$headers .= 'From: Verificador PPA <ppa@spinebone.net>' . "\r\n";

	// multiple recipients
	$to  = 'ndaza@dayscript.com' . ', '; // note the comma
	$to .= 'damaris@dayscript.com';

	// subject
	$subject = "ERROR Archivos " . $argv[1];

	// Mail it
	mail( $to, $subject, $body, $headers);

}
