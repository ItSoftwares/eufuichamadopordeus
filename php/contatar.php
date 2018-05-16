<?
require "vendor/autoload.php";

$dados = $_POST;

$email = "karina20karina@gmail.com";

if ($email==$dados['email']) {
    echo json_encode(array('estado'=>2, 'mensagem'=>"Digite outro email!"));
    exit;
}

$mail = new PHPMailer;
            
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

//$mail->addAddress("karina@ktprime.com.br", $dados['nome']);
//$mail->addAddress("itsoftwares2016@gmail.com", $dados['nome']);
$mail->addAddress("eufuichamadopordeus@gmail.com", $dados['nome']);

$mensagem = file_get_contents("../html/emailGeral.html");
$mensagem2 .= "{$dados['nome']} lhe escreveu através do formulário do site Eu Fui Chamado por Deus!<br><br>";
$mensagem2 .= "<b>Email: </b><i>{$dados['email']}</i>";
$mensagem2 .= "<br>Mensagem:<br><br>{$dados['mensagem']}";

$mensagem = str_replace("--MENSAGEM--", $mensagem2, $mensagem);

$mail->SMTPDebug = 0;                                 // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'br274.hostgator.com.br';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'notificacoes@eufuichamadopordeus.com.br';                 // SMTP username
$mail->Password = 'delso12345';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                  // TCP port to connect to

$mail->setFrom('notificacoes@eufuichamadopordeus.com.br', 'Eu Fui Chamado Por Deus');

$mail->DEBUG = 0;
$mail->Subject = 'Contato de cliente - Eu Fui Chamado por Deus';
$mail->isHTML(true);
$mail->Body = $mensagem;
$mail->CharSet = 'UTF-8';

if (!$mail->send()) {
    echo 'Message could not be sent.<pre>';
    echo $mail->ErrorInfo;
} else {
    $mail->ClearAllRecipients();
    echo json_encode(array('estado'=>1, 'mensagem'=>"Em breve você receberá uma resposta em seu email!"));
}
?>