<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form method="GET">
             <label>sitio</label><input type="text" name="pagina" value="<?php if(isset($_GET['pagina'])){echo $_GET['pagina'];}?>"><br/>
            
            <p>separa las palabras claves con comas para hacer busquedas compuestas</p>
            <p>separa las palabras claves con punto y coma anadir mas busquedas</p>
            <label>palabras claves</label>
            <textarea name="palabras_claves" rows="10" cols="40" ><?php if(isset($_GET['palabras_claves']))echo $_GET['palabras_claves'];?></textarea>
            
            
            <!--<input typeppalabras_clavesalabras_clavestext" name="palabras_claves" size="100" value="<?php if(isset($_GET['palabras_claves']))echo $_GET['palabras_claves'];?>"><br/>-->
            <br/><input type="submit">
        </form>
        <hr/>
        <?php
        set_time_limit(20000);
        // recupero la pagina
        $url = 'https://www.google.es/search?q=juegosapk';
        
        if(isset($_GET['pagina']) && isset($_GET['palabras_claves'])){
            echo 'para '. $_GET['pagina']. '<br/>';
            $gruposdeBusquedas = explode (';',$_GET['palabras_claves']);
            for ($i = 0; $i < count($gruposdeBusquedas); $i++) {
                $gruposdePalabras = explode(',',$gruposdeBusquedas[$i]);
//                print_r($gruposdePalabras);
//                echo '<br>';
                $resultado = busqueda($gruposdePalabras ,$_GET['pagina']);
                echo $gruposdeBusquedas[$i] . ' ' . $resultado. '<br/>';
            }
        }

        function busqueda($PalabrasClaves = array(),$pagina = '') {
            $posicion = 0;
            $url = 'https://www.google.es/search?q=';

            if (isset($PalabrasClaves)) {
                for ($i = 0; $i < count($PalabrasClaves); $i++)
//                echo $PalabrasClaves[]
                    if ($i == 0) {
                        $url .= urlencode (trim($PalabrasClaves[$i]));
                    } else {
                        $url .= '+' .urlencode (trim($PalabrasClaves[$i]));
                    }
            }
            
//            var_dump($url);
            for ($x = 0; $x < 100; $x+=10) {
                
                file_get_contents("https://www.google.es");
                if ($x == 0) {
//                    var_dump($url);
                    sleep(1);
//                    $html = getSslPage($url);
                    $html = file_get_contents($url);

                    $dom = new DOMDocument();
                    @$dom->loadHTML($html);
                    $xpath = new DOMXPath($dom);
                    $g = $xpath->evaluate("/html/body//cite");

                    for ($i = 0; $i < $g->length; $i++) {
                        $a = $g->item($i);
                        if (strpos($a->getAttribute('class'), 'srg') === false) {
                            $Enlaces[] = $a->nodeValue;
                            if(strpos($a->nodeValue, $pagina) !== false){
                                $posicion=  count($Enlaces);
                                break;
                            }
                        }
                    }
                    
                } else {
//                    var_dump($url . '&start=' . $x);
                    sleep(1);
//                    $html = getSslPage($url . '&start=' . $x);
                    $html = file_get_contents($url . '&start=' . $x);
                    $dom = new DOMDocument();
                    @$dom->loadHTML($html);
                    $xpath = new DOMXPath($dom);
                    $g = $xpath->evaluate("/html/body//cite");

                    for ($i = 0; $i < $g->length; $i++) {
                        $a = $g->item($i);
                        if (strpos($a->getAttribute('class'), 'srg') === false) {
                            $Enlaces[] = $a->nodeValue;
                            if(strpos($a->nodeValue, $pagina) !== false){
                                $posicion=  count($Enlaces);
                                break;
                            }
                        }
                    }
                }
                if($posicion != 0){
                    break;
                }
            }
            sleep(10);
            return $posicion;
        }

        echo '<hr>';

        
//        var_dump(file_get_contents("http://php.net"));
       
//        var_dump(getSslPage("http://php.net"));
        
//        var_dump(getSslPage('https://www.google.es/search?q=juegosapk+sniper'));
function getSslPage($url) {
    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

        ?>
        
        ver esto http://blog.jorgeivanmeza.com/2009/03/busquedas-en-google-search-desde-php/
    </body>
</html>
