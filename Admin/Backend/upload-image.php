<?php
include("./Conexiones.php");
$con = new Conexiones();

$upload_path = "../Imagenes/";

for($i = 3315; $i < sizeof($json); $i++)
{
    $articulo = $json[$i]["articulo"];
    $categoria = $json[$i]["categoria"];
    $subcategoria = $json[$i]["subcategoria"];
    $precio = $json[$i]["precio"];
    $precio = explode("$", $precio);
    $precio = explode(" ", $precio[1]);
    $precio = $precio[0];
    $unidad = $json[$i]["unidad"];
    $url_img = $json[$i]["imagen-src"];
    $arr_img = explode("/", $url_img);
    $tam = sizeof($arr_img);
    $img = $arr_img[$tam-1];

    if($img == "0000000000000-02-09-01.jpg" or $img == "0000000000000-01-01-01.jpg")
    {
        echo "ko";
        $img_name= "sinfoto.png";
        $img_name_mini = "sinfoto.png";
    }
    else
    {
        //guardar imagen
        if($url_img == "")
        {
            $img_name = "sinfoto.png";
            $img_name_mini = "sinfoto.png";
        }
        else
        {
            //img grande
            //file path to upload in the server 
            try
            {
                $tam_imagen_gde = "02-09-01";
                $url_img2 = str_replace("01-01-01", $tam_imagen_gde, $url_img);

                $file_headers = @get_headers($url_img2);
                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') 
                {
                    $img_name= "sinfoto.png";
                    echo "url grande no existe</br>";
                }
                else
                {
                    $img_name = $id_articulo."_img.jpg";
                    $file_path = $upload_path . $img_name;  
                    $file = $url_img2;
                    $size = getimagesize($file);
                    $width0 = $size[0];
                    $height0 = $size[1];
                    $ratio = $width0/$height0;
                    if($width0 > 300){
                        $width = 300;
                        $height = 300/$ratio;
                    }else{
                        $width = 300;
                        $height = 300/$ratio;
                    }
                    $src = imagecreatefromstring(file_get_contents($file));
                    $dst = imagecreatetruecolor($width, $height);
                    imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
                    imagedestroy($src);
                    //--------------------------------- GUARDAR IMAGENES -------------------------------------
                    $file_path = imagepng($dst,$file_path); 
                }
            }
            catch(Exception $e){ echo "error\n"; $img_name = "sinfoto.png"; }

            try
            {
                $file_headers = @get_headers($url_img);
                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') 
                {
                    $img_name_mini = "sinfoto.png";
                    echo "url mini no existe</br>";
                }
                else
                {
                    //img chica
                    //file path to upload in the server 
                    $img_name_mini = $id_articulo."_mini_img.jpg";
                    $file_path = $upload_path . $img_name_mini;  
                    $file = $url_img;
                    $size = getimagesize($file);
                    $width0 = $size[0];
                    $height0 = $size[1];
                    $ratio = $width0/$height0;
                    if($width0 > 150){
                        $width = 150;
                        $height = 150/$ratio;
                    }else{
                        $width = 150;
                        $height = 150/$ratio;
                    }
                    $src = imagecreatefromstring(file_get_contents($file));
                    $dst = imagecreatetruecolor($width, $height);
                    imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
                    imagedestroy($src);
                    //--------------------------------- GUARDAR IMAGENES -------------------------------------
                    $file_path = imagepng($dst,$file_path); 
                }
            }
            catch(Exception $e){ "error\n"; $img_name_mini = "sinfoto.png"; }
        }
    }  
}





