<?php

// Executa querrys
function DBexecute($query) {
    $link = DBconnect();

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    DBclose($link);

    return $result;
}

function DBcreate($tabela, array $data) {
    $data = DBescape($data);
    $campos = implode(", ", array_keys($data));
    $valores = "'" . implode("', '", $data) . "'";

    $query = "INSERT INTO {$tabela} ({$campos}) values ({$valores})";

    $link = DBconnect();

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $result = mysqli_insert_id($link);
    DBclose($link);
    
    return $result;
}

function DBselect($tabela, $param = null, $campos = '*') {
    $param = ($param) ? " " . $param : null;
    $query = "select {$campos} from {$tabela}{$param}";
    // echo $query; exit;
    $result = DBexecute($query);

    if (mysqli_num_rows($result) > 0) {
        $arr = array();

        // if (mysqli_num_rows($result)==1) {
            // $arr = mysqli_fetch_assoc($result) or die('erro na conversão para array');
        // } else {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, $row);
        }
        // }

        return $arr;
    } else {
        return [];
    }
}

function DBdelete($tabela, $param) {
    $param = ($param) ? " " . $param : null;
    $query = "DELETE from {$tabela}{$param}";

   // echo "<br>" . $query;

    $result = DBexecute($query);

    return $result;
}

function DBupdate($tabela, array $dados, $param = null) {
    $dados = DBescape($dados);
    $param = ($param) ? " " . $param : null;
    $i = 0;

    $query = null;
    
   // var_dump($dados);
    
    foreach ($dados as $key => $valor) {
        $i++;
        $chave = $key;
        
        $valor = is_numeric($valor) ? $valor : "'{$valor}'";
        
        if ($i == 1) {
            $query = "UPDATE {$tabela} set {$chave} = " . $valor;
        } else {
            $query = $query . ", {$chave} = {$valor}";
        }
        next($dados);
    }

    $query .= $param;
    // echo $query; exit;

    $result = DBexecute($query);
    return $result;
}

function limparDados($dados, $id) {
    $teste=false;
    $palavras=array("insert into","update","delete","create","values");
    if (!is_array($dados)) {
        foreach($palavras as $valor) {
            if (strpos($dados, $valor) !== FALSE) {
                $teste=true;
            }
        }
    } else {
        $arr = $dados; 
        $dados = array();
        foreach ($arr as $key => $value) {
            $key = str_replace("_", " ", preg_replace('/[^a-z0-9]/i', '_', $key));
            $value = str_replace("_", " ", preg_replace('/[^a-z0-9]/i', '_', $value));
            $dados[$key] = $value;
        }
    }
    
    return $teste;
}

function DBcreateVarios($tabela, array $data) {
    $campos = implode(", ", array_keys(end($data)));
    
    $valores="";
    $ultimo = end($data);
    foreach($data as $i) {
        $valores .= "('".implode("', '", $i)."')";
        if ($i!=$ultimo) {
            $valores .= ",";
        }
    }

    $query = "INSERT INTO {$tabela} ({$campos}) values {$valores}";
    
   // echo $query;
    $link = DBconnect();

    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $result = mysqli_insert_id($link);
    DBclose($link);
    
    return $result;
}

?>