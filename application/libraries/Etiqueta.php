<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Etiqueta
 *
 * @author cherra
 */
class Etiqueta {

    var $CI;
    
    function __construct() {
        $this->CI = & get_instance();
    }
    
    function genera($formato, $nombre, $precio, $codigo_barras, $sucursal, $copias, $referencia){
        /*$archivo = base_url()."labels/".$formato.".prn";
        $etiqueta = fopen($archivo,'r');
        if($etiqueta){*/
        $this->CI->load->helper('file');
            $contenido = read_file($this->CI->config->item('asset_path')."labels/".$formato,"r");
            //$contenido = fread($etiqueta, filesize($archivo));
            if($contenido){
                //Caracteres especiales
                $especial = array("á" => "\A0","é" => "\82","í" => "\A1","ó" => "\A2","ú" => "\A3","ñ" => "\A4","Ñ" => "\A5");

                $buscar = array("<articuloL1>","<articuloL2>","<importe>","<barcode>","<recipiente>","<copias>","<codigoproveedor>");
                $nombre = ucwords(mb_strtolower($nombre, 'UTF-8'));
                if($formato == '22.prn'){
                    $lineas = $this->str_split_unicode($nombre,18);
                }elseif($formato == '26.prn'){
                    $lineas = $this->str_split_unicode($nombre,25);
                }else
                    return false;

                    //Sustituye caracteres especiales
                $lineas[0] = strtr($lineas[0], $especial);
                if(array_key_exists(1,$lineas))
                    $lineas[1] = strtr($lineas[1], $especial);

                $codigo_barras = ">:".substr($codigo_barras, 0, 4).">5".substr($codigo_barras, 4, 4);
                $remplazo = array($lineas[0],array_key_exists(1,$lineas) ? $lineas[1] : '',"$".number_format($precio,2,'.',','),$codigo_barras,$sucursal,$copias,$referencia);
                $contenido = str_replace($buscar, $remplazo, $contenido);
                //fclose($etiqueta);
                return $contenido;
            }else{
                //fclose($etiqueta);
                return false;
            }
        /*}else
            return false;*/
    }
    
    function str_split_unicode($str, $l = 0) {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}

?>
