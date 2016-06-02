<?php
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die ("Erro ao conectar");
$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
$sql_ir = "SELECT nome_arquivo FROM imposto_arquivo WHERE matricula=$_SESSION[user_name]";
$result_ir =  mysqli_query($conn, $sql_ir);
$row_ir = mysqli_fetch_array($result_ir, MYSQL_ASSOC);
$imposto_arquivo = $row_ir['nome_arquivo'];
$file = "IR/$imposto_arquivo";
$filename = 'Imposto_Renda.pdf';
$temp_file = $imposto_arquivo;
$num_rows = mysqli_num_rows($result_ir);
if ($num_rows==1){

   //make sure no one is trying to inject anything funny
    $temp_file = str_replace('.pdf','',$temp_file);
    $temp_file = str_replace('.','',$temp_file);                //prevent file path manipulation
    $temp_file = str_replace('/','',$temp_file);                //prevent file path manipulation
    $temp_file = str_replace('%00','',$temp_file);              //prevent null char injector
    $temp_file = preg_replace('[^A-Za-z0-9]', '', $temp_file );
	$file = 'ir/'.$temp_file.'.pdf';
	
	if (file_exists($file)) {
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
	}
	
}
else{
	echo "<p><div class='alert alert-danger' role='alert' align=center>Prezado colaborador, infelizmente não há informe de rendimento neste período!</div></div></p>";
}


?>