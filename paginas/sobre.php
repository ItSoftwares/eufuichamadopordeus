<?
session_start();
require "../php/sessao_usuario.php";
// verificarSessao();
require "../php/conexao.php";

$info = DBselect("slide");

$selec = 'sobre';
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Eu Fui Chamado por Deus - Sobre</title>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="itsoftwares">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<link rel="shortcut icon" type="image/png" href="img/logo.png"/>
		<link rel="stylesheet" href="../css/geral.css" media="(min-width: 1000px)">
		<link rel="stylesheet" href="../css/sobre.css" media="(min-width: 1000px)">
		<link rel="stylesheet" href="../cssmobile/geral.css" media="(max-width: 999px)">
		<link rel="stylesheet" href="../cssmobile/sobre.css" media="(max-width: 999px)">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	
	<body>
		<? include('../html/menu.html'); ?>
	   
	   <section id="inicio">
		   <div class="fundo"></div>
		   
		   <div id="nome">
			   <h1>Sobre</h1>
		   </div>
	   </section>
		
		<section id="sobre">
			<div id="slide">
			   
				<div class="slide">
					<img src="../servidor/slide/<? echo $info[0]['img']; ?>" alt="">
					
					<div>
						<h3><? echo $info[0]['titulo']; ?></h3>
						<p><? echo $info[0]['descricao']; ?></p>
					</div>
				</div>
				<div class="slide">
					<img src="../servidor/slide/<? echo $info[1]['img']; ?>" alt="">
					
					<div>
						<h3><? echo $info[1]['titulo']; ?></h3>
						<p><? echo $info[1]['descricao']; ?></p>
					</div>
				</div><div class="slide">
					<img src="../servidor/slide/<? echo $info[2]['img']; ?>" alt="">
					
					<div>
						<h3><? echo $info[2]['titulo']; ?></h3>
						<p><? echo $info[2]['descricao']; ?></p>
					</div>
				</div>
				<img src="../img/seta-dupla.png" id="esquerda">
				<img src="../img/seta-dupla.png" id="direita">
			   <span id="barra"></span>
			</div>
			<div id="bolas">
				<span data-id='1' class="atual"></span>
				<span data-id='2'></span>
				<span data-id='3'></span>
			</div>
		</section>
		
		<? 
		include("../html/rodape.html");
		?>
	</body> 
	<script type="text/javascript">
		var info = <? echo json_encode($info); ?>
	</script>
	<script src="../js/jquery.mask.js"></script>
	<script src="../js/menu.js"></script>
	<script src="../js/sobre.js"></script>
</html>