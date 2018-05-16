<?
date_default_timezone_set("America/Sao_Paulo");
require "../conexao.php";
require "../classes/encontro.class.php";


$dados = $_POST;

$funcao = $dados['funcao'];
unset($dados['funcao']);

if ($funcao=="novo") {
    $data = str_replace("/","-",$dados['data']);
    $data_inicio = strtotime($data." ".$dados['hora_inicio']);
    $dados['data'] = $data_inicio;
    unset($dados['hora_inicio']);

    $encontro = new Encontro($dados);
    $result = $encontro->cadastrar();

    $result['encontro'] = $encontro->toArray();

    echo json_encode($result);
    exit;
} 
else if ($funcao=='inscrever') {
    $id_usuario = $dados['id_usuario'];
    unset($dados['id_usuario']);
    $dados['time'] = time();
    $encontro = new Encontro($dados);
    $result = $encontro->inscrever($id_usuario); 

    echo json_encode($result);
    exit;
}
else if ($funcao=="finalizar") {
    $encontro = new Encontro($dados);
    $encontro->finalizado = 1;
    $encontro->data_final = time();
    
    $result = $encontro->atualizar();
    
    echo json_encode($result);
    exit;
}
else if ($funcao=="atualizar") {
    $dirname="";
	$target_file="";
	$imageFileType="";

    $dados['agendado']=1;
    
    if (isset($dados['data'])) {
        $data = str_replace("/","-",$dados['data']);
        $data_inicio = strtotime($data." ".$dados['hora_inicio']);
        $dados['data'] = $data_inicio;
        unset($dados['hora_inicio']);
    }
    
	$result = array('estado'=>1, 'mensagem'=> "Encontro atualizado com sucesso!", "encontro"=>$dados);
//	var_dump($dados);
//	exit;
	
	if (count($_FILES)>0) {
		$dirname = realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."encontro".DIRECTORY_SEPARATOR);
		foreach ($_FILES as $key => $file) {
			$tem = false;
			if ($file["name"]!="") {
				$target_file = basename($file["name"]);
				$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				$tem = true;
			}
			
			if ($tem) {
				$uploadOk = 1;
				$target_file = $dirname .DIRECTORY_SEPARATOR. $dados['id']."-".$key . ".jpg";

				// VERIFICAR TAMANHO DA IMAGEM
				if ($file["size"] > 10000000) {
					$mensagem = "Tamanho máximo permitido é de 10Mb";
					$uploadOk = 0;
				}

				// FINALIZAR UPLOAD
				if ($uploadOk == 0) {
					$result = array('estado'=>2, 'mensagem'=> $mensagem);
					// SE ESTIVER TUDO CERTO FAZ O UPLOAD
				} else {
					if (move_uploaded_file($file["tmp_name"], $target_file)) {
						// TUDO OK
						$result = array('estado'=>1, 'mensagem'=> "Encontro atualizado com sucesso!");
					} else {
						$mensagem =  "Desculpe, ocorreu um erro ao enviar imagem!";
						$result = array('estado'=>2, 'mensagem'=> $mensagem);
					}
				}    
			}
		}
	}

	DBupdate("encontros", $dados, "where id={$dados['id']}");
    
	echo json_encode($result);
	exit;
}
else if ($funcao=="excluir") {
    $encontro = new Encontro($dados);
    $result = $encontro->excluir();
    
    echo json_encode($result);
    exit;
}
else if ($funcao=="pagar") {
    $id_usuario = $dados['id_usuario'];
    unset($dados['id_usuario']);
    
    $encontro = new Encontro($dados);
    $result = $encontro->pagar($id_usuario); 

    echo json_encode($result);
    exit;
}
else if ($funcao=="extra") {
    $dados['time'] = time();
    
    $banco = $dados['banco'];
    unset($dados['banco']);
    
    $result = DBselect($banco, "where id_usuario={$dados['id_usuario']} and id_encontro={$dados['id_encontro']}");
    
    if (isset($result)) {
        if ($banco=="erro_pagamento") echo json_encode(array("estado"=>2, "mensagem"=>"Você já nos mandou esse aviso!"));
        else echo json_encode(array("estado"=>2, "mensagem"=>"Ok, já registramos seu interesse em participar deste encontro!"));
        exit;
    }
    
    DBcreate($banco, $dados);
    
    if ($banco=="erro_pagamento") echo json_encode(array("estado"=>1, "mensagem"=>"Ok. Entraremos em contato! <br>E sua vaga já está reservada."));
    else echo json_encode(array("estado"=>1, "mensagem"=>"Sua vaga está reservada. <br>Aguardamos por vc!"));
}
else if ($funcao=="extra_atualizar") {
    $banco = $dados['banco'];
    unset($dados['banco']);
    
    DBupdate($banco, $dados, "where id={$dados['id']}");
    
    echo json_encode(array("estado"=>1));
}

//var_dump($dados);
?>