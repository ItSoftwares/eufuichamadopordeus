<?
date_default_timezone_set("America/Sao_Paulo");
require "../conexao.php";
session_start();

$result = "";
$dados = $_POST;

if ($dados['funcao']=="change") {
	unset($dados['funcao']);
	
	DBupdate("aviso", array('ativo'=>$dados['ativo']), "where id=1");
	
	echo json_encode(array('estado'=>1, 'mensagem'=> "Aviso atualizado com sucesso!"));
	exit;
} else if ($dados['funcao']=="atualizar") {
	unset($dados['funcao']);
	$dirname="";
	$target_file="";
	$imageFileType="";

	$result = array('estado'=>1, 'mensagem'=> "Aviso atualizado com sucesso!");

//	var_dump($dados);
//	exit;
	
	if (count($_FILES)>0) {
		$dirname = realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."aviso".DIRECTORY_SEPARATOR);
		foreach ($_FILES as $key => $file) {
			$tem = false;
			if ($file["name"]!="") {
				$target_file = basename($file["name"]);
				$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
				$tem = true;
			}
			
			if ($tem) {
				$uploadOk = 1;
				$target_file = $dirname .DIRECTORY_SEPARATOR. str_replace("imagem", "aviso", $key) . ".jpg";

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
						$result = array('estado'=>1, 'mensagem'=> "Conteudo atualizado com sucesso!");
					} else {
						$mensagem =  "Desculpe, ocorreu um erro ao enviar imagem!";
						$result = array('estado'=>2, 'mensagem'=> $mensagem);
					}
				}    
			}
		}
	}

	DBupdate("aviso", $dados, "where id=1");

	echo json_encode($result);
	exit;
}

?>