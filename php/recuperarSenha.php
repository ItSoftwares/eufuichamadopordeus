<?
require "conexao.php";
require "vendor/autoload.php";

$dados = DBescape($_POST);
$email = $dados['email'];
$funcao = 0;
$result = "";

// verificador se é adm
$result = DBselect("adm", "where email='{$email}'");

if (count($result)>0) {
    $funcao=1;
} else {
    // verificador se é usuario
    $result = DBselect("usuario", "where email='{$email}'");

    if (count($result)>0) {
        $funcao=2;
    }
}

if ($funcao==1) {
    echo json_encode(array('estado'=>2, 'mensagem'=>"Digite um email de usuário comum, pois a senha do ADM é criptografada!"));
} else if ($funcao==2) {
    $url = "http://eufuichamadopordeus.com.br";

    $mensagem = file_get_contents("../html/emailGeral.html");
    $mensagem2 = "Olá você acabou de solicitar recuperação de senha para sua conta, abaixo estará sua senha!<br><br>";
    $mensagem2 = "Informações para Login:<br> <b>Email: </b><i>{$email}</i>";
    $mensagem2 .= "<br><b>Senha: </b><i>{$result[0]['senha']}</i>";
    $mensagem2 .= "<br>";
    $mensagem2 .= "<a href='".$url."'>Link para Login!</a>";

    $mensagem = str_replace("--MENSAGEM--", $mensagem2, $mensagem);

    $mail = new PHPMailer;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->addAddress($email, "Usuario");

    $mail->SMTPDebug = 0;                            // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'br274.hostgator.com.br';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'notificacoes@eufuichamadopordeus.com.br';                 // SMTP username
    $mail->Password = 'delso12345';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                  // TCP port to connect to

    $mail->setFrom('notificacoes@eufuichamadopordeus.com.br', 'Eu Fui Chamado Por Deus');

    $mail->DEBUG = 0;
    $mail->Subject = 'Recuperação de senha - Eu Fui Chamado Por Deus';
    $mail->isHTML(true);
    $mail->Body = $mensagem;
    $mail->CharSet = 'UTF-8';
    
    if (!$mail->send()) {
//        echo 'Message could not be sent.<pre>';
//        echo $mail->ErrorInfo;
    } else {
        $mail->ClearAllRecipients();
    }
    
    echo json_encode(array('estado'=>1, 'mensagem'=>"Enviamos um email com as informações de recuperação!"));
} else {
    echo json_encode(array('estado'=>2, 'mensagem'=>"Email inválido!"));
}
?>