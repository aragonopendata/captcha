<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SITAR : Sistema de informaci&oacute;n territorial de Arag&oacute;n &shy; Verificación para descargas.</title>
<link rel="shortcut icon" href="../i/favicon.ico" />
<meta name="lang" content="es" />
<meta name="organization" content="Centro de Informacion Territorial de Aragon - GOBIERNO DE ARAGON" />
<meta name="locality" content="Zaragoza, Spain" />
<meta name="keywords" content="SITAR, CINTA, informacion, descarga" />
<meta name="title" content="SITAR : Sistema de informacion territorial de Aragon - Altimetria 1:1.000" />
<meta name="description" content="SITAR : Sistema de informacion territorial de Aragon - Altimetria 1:1.000" />
<meta name="distribution" content="global" />
<meta name="resource-type" content="Document" />
<meta name="robots" content="none" />
<meta name="revisit-after" content="3 days" />
<meta http-equiv="Pragma" content="no-cache" />
<meta name="Author" content="CINTA" />


<style type="text/css">
body { font-family: Arial; font-size: 12px; padding: 20px; }
#result { border: 1px solid #CC0000; background-color: #CC0000; color: #FFFFFF; width: 250px; margin: 0 0 5px 0; padding: 2px 20px; font-weight: bold; }
#change-image { font-size: 0.8em; }
#form{border: 1px solid rgb(148, 163, 196);margin: 0px 0px 1px;width:290px;}
form h3{background: rgb(236, 239, 245);display:block;margin: 0px 0px 0px;padding: 1px 1px;}
</style>
</head>


<body>

<div style="float: none">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2">
				&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center">
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left">
						<img src="logo_GobAragon.png" alt="Gobierno de Arag&oacute;n" />
					</td>
					<td align="right">
						<img src="LogoSITARPositivo.png" alt="Sistema de informaci&oacute;n territorial de Arag&oacute;n" />
					</td>
				</tr>

			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
				&nbsp;
		</td>
	</tr>

</table>
<div style="width: 45%; float: right" align="center">
    <a href="#" onclick="self.close()" ><img src="onebit_33.png" width="10" height="10" border="0" />Cerrar ventana</a>
 </div>

 <div id="cargando" name="cargando" style="width: 100%; visibility: hidden" align="center">
      <br />
    <span class="">
        <img src="394.GIF" width="55" height="55" border="0" align="absmiddle" />
        <img src="A.png"   width="180" height="30"  border="0" align="absmiddle" />
    </span>
 </div>

<?php
//Iniciamos session de PHP
session_start();


if(isset($_GET['descarga']))
{
  echo   "<script type='text/javascript'>
              window.close();
          </script>";
}


//Construimos la URL definitiva de descarga
//seteamos las variables


if (isset ($_GET['fichero']) )  { $_SESSION['fileadescargar'] = $_GET['fichero'];}
if (isset ($_GET['inzip']) ) { $_SESSION['filedelzip'] = $_GET['inzip'];}
//$_SESSION['urldefinitiva']  = 'http://sita3.aragon.local:80/captcha/descarga.php?file=';

$_SESSION['urldefinitiva']  = 'descarga.php?fichero=';
$_SESSION['urldefinitiva'] .= $_SESSION['fileadescargar'];
$_SESSION['urldefinitiva'] .= '&inzip=';
$_SESSION['urldefinitiva'] .= $_SESSION['filedelzip'];

    //echo  $_SESSION['urldefinitiva'];exit;



/********************************************/
/** Validacion captcha y control de session */
/********************************************/

/* Si pasa la validacion del captcha se establece la variable de session
* $_SESSION['tienesesion'] a 'SI'
* se cierra la ventana emergente del captcha
* y redirecciona a descarga.php con la url completa.
*
* Si ya existe la session con captcha validado
* se redirecciona a descarga.php con la URL formada
*
*
* Si no se introduce correctamente el captcha la variable
* $_SESSION['tienesesion'] tiene valor 'NO' y se repetira
* la petición
*/

if ( $_SESSION['tienesesion']=='NO' || !isset($_SESSION['tienesesion']) )
{


if (!empty($_GET['captcha'])) {

    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
        $captcha_message = "Captcha incorrecto"; $style = "background-color: #FF0000";

            $_SESSION['tienesesion']='NO';

            $_SESSION['urldefinitiva']  = 'descarga.php?fichero=';
            $_SESSION['urldefinitiva'] .= $_SESSION['fileadescargar'];
            $_SESSION['urldefinitiva'] .= '&inzip=';
            $_SESSION['urldefinitiva'] .= $_SESSION['filedelzip'];


    } else {
        $captcha_message = '<div id="result"><img src="resources/ajax-loader3.gif" alt="" /></div>';
        $style = "background-color: #CCFF99";

        $_SESSION['tienesesion']='SI';    //Ponemos la variable de control de session a SI

        //echo $_GET['Udef'];exit;
        echo   "<script type='text/javascript'>
                window.close();
               </script>";            //Cerramos la ventana del captcha

       header ("Location: ".$_GET['Udef']."");            //Redireccionamos a la url de descarga

    }
    $request_captcha = htmlspecialchars($_REQUEST['captcha']);

    echo <<<HTML
        <div id="result" >
        $captcha_message
        </div>
HTML;



}
//echo $_SESSION['tienesesion'];
?>
<h3>Verificación para descarga</h3>
<form id="form" method="GET">
<h3>Escriba el texto de la imagen</h3>
<div style="padding:2px">
<img src="captcha.php" id="captcha" /><br/>
<!-- CHANGE TEXT LINK -->
<a href="#" onclick="document.getElementById('captcha').src='captcha.php?'+Math.random();" id="change-image">Recargar Captcha.</a><br/><br/>
<input type="text" name="captcha" id="captcha-form" />
<input type="submit" value="Confirmar" />
<input type="text"  name="Udef" id="Udef" style="visibility: hidden" readonly="readonly" value="<?php echo $_SESSION['urldefinitiva'];?>" />
</div>
</form>
<?php ;}else{
  //echo 'REDIRECCIONAMOS'; echo $_SESSION['urldefinitiva'];//exit;
  echo   "<script type='text/javascript'>
          function redireccionar()
          {
          document.getElementById('cargando').style.visibility = 'hidden';
          window.location='".$_SESSION['urldefinitiva']."';
          ;}

             document.getElementById('cargando').style.visibility = 'visible';

           setTimeout ('redireccionar()', 2000);


       </script>";

        /*  echo   "<script type='text/javascript'>
          window.close();
        </script>";
     header ("Location: ".$_SESSION['urldefinitiva']."");*/
}
$_SESSION['urldefinitiva']='';
$_SESSION['filedelzip']='';
$_SESSION['fileadescargar']='';
unset($_SESSION['captcha']);
unset($_SESSION['urldefinitiva']);
?>



</div>

</body>
</html>
