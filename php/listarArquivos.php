<?
function listar($diretorio) {
    $array = array();
    $caminhos = array();
    $informacoes = array();
    
    $lista = scandir($diretorio);
    $lista = array_diff($lista, ["..","."]);
    
    foreach ($lista as $temp) {
        if (is_dir($diretorio.DIRECTORY_SEPARATOR.$temp) && file_exists($diretorio.DIRECTORY_SEPARATOR.$temp)) {
            $result = listar($diretorio.DIRECTORY_SEPARATOR.$temp);
            $array[$temp] = $result['nomes'];
            $caminhos[$temp] = $result['caminhos'];
            $informacoes[$temp] = $result['informacoes'];
        } else {
            array_push($array, $temp);
            array_push($caminhos, $diretorio.DIRECTORY_SEPARATOR.$temp);
            array_push($informacoes, pathinfo($diretorio.DIRECTORY_SEPARATOR.$temp));
        }
    }
    return array("nomes"=>$array, "caminhos"=>$caminhos, "informacoes"=> $informacoes);
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

function removerCaracteres($str) {
    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
    $str = preg_replace('/[éèêë]/ui', 'e', $str);
    $str = preg_replace('/[íìîï]/ui', 'i', $str);
    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
    $str = preg_replace('/[úùûü]/ui', 'u', $str);
    $str = preg_replace('/[ç]/ui', 'c', $str);
    return $str;
}
?>