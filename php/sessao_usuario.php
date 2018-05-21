<?
//echo verificarSessao();
function verificarSessao($adm = 0) {
    $result = 0;
    if (!isset($_SESSION)) session_start();
    
    if (!isset($_SESSION['expire'])) $result = 1;
    else if (!isset($_SESSION['donoSessao'])) $result = 2;
    
//    echo $expire; exit;
//    echo "<pre>"; var_dump($_SESSION); exit;
    $expire = $_SESSION['expire'];
    $dono = $_SESSION['donoSessao'];
    
    if (time()>intval($expire)+(3*60*60)) {
        $result = 1;
    } else if (md5('sat'.$expire) != $dono) {
        $result = 2;
    }
    
    if ($adm!=0) {
        if (!array_key_exists("adm", $_SESSION)) $result = 3;
    }
    
    if ($result>0 and $_SESSION['lembrar']==0) {
        if ($result==3) {
            $_SESSION['info_msg'] = "Acesso restrito";
        } else {
            $_SESSION['erro_msg'] = "Faça Login ou cadastre-se";
        }
//        var_dump($_SESSION);
//        echo "<br>".(intval($expire)+(3*60*60))."<br>";
//        var_dump(time()>intval($expire)+(3*60*60));
//        exit;
        header("location: login?set=1");
    }
}

?>