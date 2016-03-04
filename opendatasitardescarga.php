<?php

include ('getRealIP.php'); //Fichero que nos devolverá la direccion IP real del cliente, aun cuando navegue a traves de proxy

//Iniciamos sesion
session_start();

// Comprobamos si han pasado el parametro fichero
if (!empty($_GET['fichero'])){
                        $_SESSION['extension'] = strtolower(substr(strrchr($_GET['fichero'], "."), 1));

                            }else{
                             header ("Location: http://sitar.aragon.es/datosdescarga/404.htm");  //Reenviamos en caso de que el parametro esté vaío
                            }
//******************************************************************************//
//*************************CASO de Fichero NORMAL. NO ZIP************************//
//******************************************************************************//


                // Path to downloadable files (will not be revealed to users so they will never know your file's real address)
                $hiddenPath = "../03_datos/";

                // VARIABLES
                $_SESSION['IP'] = getRealIP();
                $_SESSION['HOY'] = date("d.m.Y-H:i:s");
                if (!empty($_GET['fichero'])){
                $_SESSION['file']  = str_replace('%20', ' ', $_GET['fichero']);
                }
                //Construimos la URL definitiva de descarga
                if (isset ($_GET['fichero']) ) { $_SESSION['fileadescargar'] = $_SESSION['file'];}
                $_SESSION['rutadescarga']   = $hiddenPath;
                $_SESSION['rutadescarga'] .=  $_SESSION['fileadescargar'];
                $_SESSION['file_real'] =  $_SESSION['rutadescarga'];

              // echo $_SESSION['file_real'];exit;


                // Si se nos ha pasado un file y si el file solicitado existe
                if (!empty ($_SESSION['fileadescargar'] ) && file_exists($_SESSION['file_real'])){

                      //echo 'ENTRO'; exit;

                // Determine correct MIME type
                switch($_SESSION['extension']){
                case "asf": $_SESSION['TYPE'] = "video/x-ms-asf"; break;
                case "asf": $_SESSION['TYPE'] = "video/x-ms-asf"; break;
                case "avi": $_SESSION['TYPE'] = "video/x-msvideo"; break;
                case "exe": $_SESSION['TYPE'] = "application/octet-stream"; break;
                case "mov": $_SESSION['TYPE'] = "video/quicktime"; break;
                case "mp3": $_SESSION['TYPE'] = "audio/mpeg"; break;
                case "mpg": $_SESSION['TYPE'] = "video/mpeg"; break;
                case "mpeg": $_SESSION['TYPE'] = "video/mpeg"; break;
                case "rar": $_SESSION['TYPE'] = "encoding/x-compress"; break;
                case "txt": $_SESSION['TYPE'] = "text/plain"; break;
                case "wav": $_SESSION['TYPE'] = "audio/wav"; break;
                case "wma": $_SESSION['TYPE'] = "audio/x-ms-wma"; break;
                case "wmv": $_SESSION['TYPE'] = "video/x-ms-wmv"; break;
                case "zip": $_SESSION['TYPE'] = "application/x-zip-compressed"; break;
                case "pdf": $_SESSION['TYPE'] = "application/pdf"; break;
                default: $_SESSION['TYPE'] = "application/force-download"; break;
                }

                // Fix IE bug [0]
                //$header_file = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $file, substr_count($file, '.') - 1) : $file;
                $_SESSION['nameoffile'] = strtolower(substr(strrchr($_SESSION['file'], "/"), 1));

                // Prepare headers
                /*header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Type: application/force-download");
                header("Accept-Ranges: bytes");
                header("Content-Disposition: attachment; filename=\"" . $_SESSION['nameoffile'] . "\";");

                header("Content-Length: " . filesize($_SESSION['file_real']));*/

                // Send file for download
                 ob_clean(); flush();
                     if(readfile($_SESSION['file_real'])){
                       //se ha enviado bien
                      $_SESSION['fp'] = fopen('descargas.log', 'a');
                      fwrite($_SESSION['fp'], $_SESSION['file']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
					  fwrite($_SESSION['fp'], 'SI');fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);
                     }

                     // Send file for download
/*if ($_SESSION['stream'] = fopen($_SESSION['file_real'], 'rb')){
while(!feof($_SESSION['stream']) && connection_status() == 0){
//reset time limit for big files
set_time_limit(0);
print(fread($_SESSION['stream'],1024*8));
flush();
}
fclose($_SESSION['stream']);
}*/

                     else{
                      //No se ha enviado bien o no se ha podido leer...
                      $_SESSION['fp'] = fopen('errores.log', 'a');
                      fwrite($_SESSION['fp'], $_SESSION['file_real']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "Error en descarga");fwrite($_SESSION['fp'], "   ");
					  fwrite($_SESSION['fp'], 'SI');fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);
                     };
                flush();
                }else{
                  //echo '404';
                  //// (File NOT found) Error 404
                      $_SESSION['fp'] = fopen('errores.log', 'a');
                      if ($_SESSION['fileadescargar']==''){fwrite($_SESSION['fp'], 'NULO');fwrite($_SESSION['fp'], "   ");}
                      if ($_SESSION['fileadescargar']!=''){fwrite($_SESSION['fp'], $_SESSION['fileadescargar']);fwrite($_SESSION['fp'], "   ");}
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "ERROR 404");fwrite($_SESSION['fp'], "   ");
					  fwrite($_SESSION['fp'], 'SI');fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);
                      header ("Location: http://sitar.aragon.es/datosdescarga/404.htm");
                      echo   "<script type='text/javascript'>
                                                 window.location='http://sitar.aragon.es/datosdescarga/404.htm';
                                              </script>";
                                          exit;
                }

                

//*************************FIN CASO de Fichero NORMAL NO ZIP************************//

$_SESSION['rutadescarga'] = '';
$_SESSION['file']='';
$i='';

?>