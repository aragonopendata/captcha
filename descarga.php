<?php
include ('getRealIP.php'); //Fichero que nos devolverá la direccion IP real del cliente, aun cuando navegue a traves de proxy

//Si no tiene sesion definida forzamos cierre de la ventana
if ( $_SESSION['tienesesion']=='NO' || empty($_GET['fichero']) )
{
  echo   "<script type='text/javascript'>
             window.close();
          </script>";
}

// Obtenemos la extension del fichero solicitado para saber si es un ZIP
if (!empty($_GET['fichero'])){
                        $_SESSION['extension'] = strtolower(substr(strrchr($_GET['fichero'], "."), 1));

                            }else{
                             header ("Location: http://sitar.aragon.es/datosdescarga/404.htm");  //Reenviamos en caso de que el parametro esté vaío
                            }
//******************************************************************************//
//*************************CASO de Fichero NORMAL. NO ZIP************************//
//******************************************************************************//
if (empty($_GET['inzip']))
{
//No se ha especificado el segundo parametro....
    echo   "<script type='text/javascript'>
             document.getElementById('cargando').style.visibility = 'visible';
          </script>";


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

                //echo $_SESSION['fileadescargar'];exit;


                // Si se nos ha pasado un file y si el file solicitado existe
                if (!empty ($_SESSION['fileadescargar'] ) && file_exists($_SESSION['file_real']))
                {
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


                //Metodo Original
                // Prepare headers
                 /* header("Pragma: public");
                  header("Expires: 0");
                  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                  header("Cache-Control: public", false);
                  header("Content-Description: File Transfer");
                  header("Content-Type: " . $type);
                  header("Accept-Ranges: bytes");
                  //header("Content-Disposition: attachment; filename=\"" . $header_file . "\";");
                  header("Content-Disposition: attachment; filename=\"" . $_SESSION['nameoffile'] . "\";");
                  header("Content-Transfer-Encoding: binary");
                  header("Content-Length: " . filesize($_SESSION['file_real']));
                    // Send file for download
                    if ($stream = fopen($_SESSION['file_real'], 'rb')){
                    while(!feof($stream) && connection_status() == 0){
                    //reset time limit for big files
                    set_time_limit(0);
                    print(fread($stream,1024*8));
                    flush();
                    }
                    fclose($stream);
                    }*/





                // Prepare headers
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public", false);
                header("Content-Description: File Transfer");
                header("Content-Type: " . $_SESSION['TYPE']);
                header("Accept-Ranges: bytes");
                header("Content-Disposition: attachment; filename=\"" . $_SESSION['nameoffile'] . "\";");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . filesize($_SESSION['file_real']));


                /*var_dump(headers_list());
                flush();
                print_r(apache_response_headers());
                echo '<br><br><br><br><br>';

                exit;*/

                // Send file for download
                     ob_clean(); flush();
                     if(readfile($_SESSION['file_real'])){
                       //se ha enviado bien
                      $_SESSION['fp'] = fopen('descargas.log', 'a');
                      fwrite($_SESSION['fp'], $_SESSION['file_real']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);

                       }

                     else{
                      //No se ha enviado bien o no se ha podido leer...
                      $_SESSION['fp'] = fopen('errores.log', 'a');
                      fwrite($_SESSION['fp'], $_SESSION['file_real']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "Error en descarga");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);
                      echo   "<script type='text/javascript'>
                               window.close();
                              </script>";
                     };
                flush();
                }else{
                  //// (File NOT found) Error 404
                      $_SESSION['fp'] = fopen('errores.log', 'a');
                      if ($_SESSION['fileadescargar']==''){fwrite($_SESSION['fp'], 'NULO');fwrite($_SESSION['fp'], "   ");}
                      if ($_SESSION['fileadescargar']!=''){fwrite($_SESSION['fp'], $_SESSION['fileadescargar']);fwrite($_SESSION['fp'], "   ");}
                      fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                      fwrite($_SESSION['fp'], "ERROR 404");
                      fwrite($_SESSION['fp'], "\n");
                      fclose($_SESSION['fp']);
                      header ("Location: http://sitar.aragon.es/datosdescarga/404.htm");
                      echo   "<script type='text/javascript'>
                                                 window.location='http://sitar.aragon.es/datosdescarga/404.htm';
                                              </script>";
                                          exit;
                }

}
//*************************FIN CASO de Fichero NORMAL NO ZIP************************//


//******************************************************************************//
//*************************CASO de Fichero ZIP *********************************//
//******************************************************************************//
if ( ($_SESSION['extension']=='zip') &&  (!empty($_GET['inzip']))  )
{
  $_SESSION['Finzip'] =  str_replace('%20', ' ', $_GET['inzip']);
  $_SESSION['extensionZ'] = strtolower(substr(strrchr($_SESSION['Finzip'], "."), 1));
             // Tomamos la extension para determinar el MIME
                switch($_SESSION['extensionZ']){
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
                default: $_SESSION['TYPE'] = "application/force-download"; break;
                }

                //echo  $_SESSION['TYPE'];exit;


        //Si es un zip y recibimos el segundo parametro del fichero dentro del zip....
                if($_SESSION['extension']=='zip' && $_SESSION['Finzip']!='' )
                {
                require_once('pclzip.lib.php'); //Clase que usaremos para explorar el ZIP

                // Path to downloadable files (will not be revealed to users so they will never know your file's real address)
                $hiddenPath = "../03_datos/";
                // VARIABLES
                $_SESSION['IP'] = getRealIP();
                $_SESSION['HOY'] = date("d.m.Y-H:i:s");
                if (!empty($_GET['fichero'])){
                $_SESSION['file']  = str_replace('%20', ' ', $_GET['fichero']);
                }
                //Construimos la URL definitiva de descarga
                $_SESSION['rutadescarga'] = $hiddenPath;
                $_SESSION['rutadescarga'] .= $_SESSION['file'];


                //mostrar el fichero
                  //echo $_SESSION['rutadescarga'];echo $_SESSION['Finzip'] ;  exit;


                $_SESSION['archivo_zip'] = new PclZip($_SESSION['rutadescarga']); //Cargamos el archivo en un nuevo objeto del tipo pclzip

        /* Ejecuta la extraccion del archivo en el directorio tmp_captcha */
            $_SESSION['extractor'] = $_SESSION['archivo_zip']->extract( PCLZIP_OPT_PATH , 'tmp_captcha',PCLZIP_OPT_BY_NAME,$_SESSION['Finzip']);
            $_SESSION['error_EX'] = $_SESSION['archivo_zip']->errorCode();

                    /* Gestionar error ocurrido (si $_SESSION['archivo_zip']->extract retorna cero a $_SESSION['extractor']) */
                    if ( $_SESSION['error_EX'] !=0 ) {
                        $_SESSION['error_enextra']  = "ERROR. Codigo: ".$_SESSION['archivo_zip']->errorCode()." ";
                        $_SESSION['error_enextra'] .= "Nombre: ".$_SESSION['archivo_zip']->errorName()." ";
                        $_SESSION['error_enextra'] .= "Descripcion: ".$_SESSION['archivo_zip']->errorInfo();
                                           //echo  $_SESSION['error_enextra'];exit;

                                          //Escribimos en log de errores y cerramos ventana
                                                                $fp = fopen('errores.log', 'a');
                                                                fwrite($fp, $_SESSION['archivo_zip']);fwrite($fp, "   ");
                                                                fwrite($fp, $_SESSION['Finzip']);fwrite($fp, "   ");
                                                                fwrite($fp, $_SESSION['error_enextra']);fwrite($fp, "   ");
                                                                fwrite($fp, $_SESSION['HOY']);fwrite($fp, "   ");
                                                                fwrite($fp, $_SESSION['IP']);fwrite($fp, "   ");
                                                                fwrite($fp, "\n");
                                                                fclose($fp);
                                                            flush();
                                                            echo   "<script type='text/javascript'>
                                                                       window.location='http://sitar.aragon.es/datosdescarga/captcha/error_zip.htm';
                                                                    </script>";
                                                                exit;
                    }

             //exit;


if ( $_SESSION['error_EX'] == 0 ) {
   //echo "Archivo extraido existosamente!"; exit;

                        //Procedemos a la descarga...
                            // Prepare headers
                                header("Pragma: public");
                                header("Expires: 0");
                                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                                header("Cache-Control: public", false);
                                header("Content-Description: File Transfer");
                                header("Content-Type: " . $_SESSION['extensionZ']);
                                header("Accept-Ranges: bytes");

                                header("Content-Disposition: attachment; filename=\"" . $_SESSION['Finzip'] . "\";");
                                header("Content-Transfer-Encoding: binary");
                                header("Content-Length: " . filesize("tmp_captcha/".$_SESSION['Finzip']));


                            //echo "tmp_captcha/".$_SESSION['Finzip'];

            // Send file for download
                   ob_clean();
                   flush();
                    //readfile("tmp_captcha/".$_SESSION['Finzip']);exit;

                    if( readfile("tmp_captcha/".$_SESSION['Finzip']) )
                     {
          		      /* Eliminamos el archivo extraido */
          			  //$eliminar = $_SESSION['archivo_zip']->delete( PCLZIP_OPT_BY_NAME , "tmp_captcha/".$_SESSION['Finzip'] );
          			   unlink("tmp_captcha/".$_SESSION['Finzip']);
                             //Escribimos el LOG
                               //se ha enviado bien
                                $_SESSION['fp'] = fopen('descargas.log', 'a');
                                fwrite($_SESSION['fp'], $_SESSION['Finzip']);fwrite($_SESSION['fp'], "   ");
                                fwrite($_SESSION['fp'], $_SESSION['HOY']);fwrite($_SESSION['fp'], "   ");
                                fwrite($_SESSION['fp'], $_SESSION['IP']);fwrite($_SESSION['fp'], "   ");
                                fwrite($_SESSION['fp'], "\n");
                                fclose($_SESSION['fp']);


                               echo   "<script type='text/javascript'>
                                 window.close();
                              </script>";
                     }else{
                            //No ha podido leerse correctamente
                            //Escribimos en log de errores y cerramos ventana
                                            $fp = fopen('errores.log', 'a');
                                            fwrite($fp, $_SESSION['rutadescarga']);fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['Finzip']);fwrite($fp, "   ");
                                            fwrite($fp, " Error lectura zip extraido");fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['HOY']);fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['IP']);fwrite($fp, "   ");
                                            fwrite($fp, "\n");
                                            fclose($fp);
                                        flush();
                                        echo   "<script type='text/javascript'>
                                                 window.close();
                                              </script>";
                                          exit;

                     }

                }
            }else{

                //El fichero NO está dentro del ZIP
                 //Escribimos en log de errores y cerramos ventana
                                            $fp = fopen('errores.log', 'a');
                                            fwrite($fp, $_SESSION['rutadescarga']);fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['Finzip']);fwrite($fp, "   ");
                                            fwrite($fp, " El fichero no está en el ZIP");fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['HOY']);fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['IP']);fwrite($fp, "   ");
                                            fwrite($fp, "\n");
                                            fclose($fp);
                                        flush();
                                        echo   "<script type='text/javascript'>
                                                 window.close();
                                              </script>";
                                          exit;



                }



          }//Segundo FOR

$_SESSION['rutadescarga'] = '';
$_SESSION['file']='';
$i='';

//************************* FIN CASO de Fichero ZIP *********************************//
//Descartes.....








                      // Send file for download
                              /*  if ($stream = fopen($array['content'], 'rb')){
                                while(!feof($stream) && connection_status() == 0){
                                //reset time limit for big files
                                set_time_limit(0);
                                if (!fread($stream, 1024*8))
                                            {
                                             // La descarga no ha podido realizarse.....
                                             //escribimos en el log de errores
                                              $fp = fopen('errores.log', 'a');

                                              fwrite($fp, $_SESSION['file_real']);fwrite($fp, "   ");
                                              fwrite($fp, $pais);fwrite($fp, "   ");
                                              fwrite($fp, $ciudad);fwrite($fp, "   ");
                                              fwrite($fp, $_SESSION['hoy']);fwrite($fp, "   ");
                                              fwrite($fp, $ip);fwrite($fp, "   ");
                                              fwrite($fp, $ip);fwrite($fp, "Error lectura del fichero");
                                              fwrite($fp, "\n");
                                              fclose($fp);
                                              header ("Location: http://sitar.aragon.es/datosdescarga/404.htm");
                                            }
                                            print(fread($stream,1024*8));
                                             // si no ha habido error escribimos en el log de descarga
                                            $fp = fopen('descargas.log', 'a');
                                            fwrite($fp, $file);fwrite($fp, "         ");
                                            fwrite($fp, $_SESSION['hoy']);fwrite($fp, "   ");
                                            fwrite($fp, $ciudad);fwrite($fp, "   ");
                                            fwrite($fp, $_SESSION['hoy']);fwrite($fp, "   ");
                                            fwrite($fp, $ip);fwrite($fp, "   ");
                                            fwrite($fp, "\n");
                                            fclose($fp);
                                            flush();
                                            }
                                            fclose($stream);
                                            }*/

?>