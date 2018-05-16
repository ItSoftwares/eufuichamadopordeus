<?
require "../conexao.php";
require "../listarArquivos.php"; 

$dados = $_POST;

$id = DBcreate("album", array('titulo'=>$dados['titulo'], 'descricao'=> $dados['descricao'], 'time'=>time()));

header("content-type: image/your_image_type");
    
$qtd = count($_FILES['imagens']['name']);
//echo $qtd;

$dirname = realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."fotos".DIRECTORY_SEPARATOR);

if (!mkdir($dirname.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR, 0755, true)) {
    $result = array('estado'=>2, 'mensagem'=> "Erro ao criar álbum tente novamente mais tarde!");
    echo json_encode($result);
    exit;
}

$dirname = $dirname.DIRECTORY_SEPARATOR.$id;

$arquivos=array();

for ($i=0; $i<$qtd; $i++) {
    $target_file = $dirname .DIRECTORY_SEPARATOR. basename($_FILES["imagens"]["name"][$i]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $target_file = $dirname .DIRECTORY_SEPARATOR. $_FILES['imagens']['name'][$i];
    $uploadOk = 1;

    // VERIFICAR SE arquivo JÁ EXISTE
    if (file_exists($target_file)) {
        $mensagem = "Já existe um arquivo com o nome {$_FILES['imagens']['name'][$i]}!";
        $uploadOk = 0;
    }

    // VERIFICAR TAMANHO DA IMAGEM
    if ($_FILES["imagens"]["size"][$i] > 10000000) {
        $mensagem = "Tamanho máximo permitido é de 10Mb";
        $uploadOk = 0;
    }

    // FINALIZAR UPLOAD
    if ($uploadOk == 0) {
        json_encode(array('estado'=>2, 'mensagem'=> $mensagem));
        break;
    // SE ESTIVER TUDO CERTO FAZ O UPLOAD
    } else {
        if (move_uploaded_file($_FILES["imagens"]["tmp_name"][$i], $target_file)) {
            // TUDO OK
            array_push($arquivos, pathinfo($target_file));
        } else {
            $mensagem =  "Desculpe, ocorreu um erro ao enviar o arquivo!";
            $result = array('estado'=>2, 'mensagem'=> $mensagem);
        }
    }
}

$lista = listar(realpath("../..".DIRECTORY_SEPARATOR."servidor".DIRECTORY_SEPARATOR."fotos".DIRECTORY_SEPARATOR.$id));
$lista = utf8ize($lista);
$dados['id'] = $id;
$result = array('estado'=>1, 'arquivos' => $arquivos, 'mensagem'=>"Arquivos enviadas com sucesso, a página será atualizada!", 'lista'=>$lista, 'novo'=> $dados);

echo json_encode($result);
exit;
?>