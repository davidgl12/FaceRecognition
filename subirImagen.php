<?php
$nombre = $_POST["nombre"];

if(!file_exists("Validar/".$nombre.".jpg")){
    $data = $_POST["imgWebcam"];
    $arreglo = explode(',', $data);
    $data = base64_decode($arreglo[1]);
    file_put_contents('Validar/'.$nombre.'.jpg', $data);
}

if(!file_exists("Images/".$nombre)){
    mkdir("Images/".$nombre);
}

$target_file = "Images/" . $nombre . "/" . basename($_FILES["imgIdentificacion"]["name"]);
$uploadOk = true;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = false;
}
else if(isset($_POST["submit"])) {// Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["imgIdentificacion"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = true;
  } else {
    echo "File is not an image.";
    $uploadOk = false;
  }
}

if($uploadOk){
    move_uploaded_file($_FILES["imgIdentificacion"]["tmp_name"], $target_file);
    echo "Se subió el archivo " . htmlspecialchars($_FILES["imgIdentificacion"]["name"]);
}else{
    echo "No se pudo subir el archivo";
}

$comando = escapeshellcmd("conda activate virenv && python faceEncoder.py ".$nombre."&& python fromImage.py ".$nombre);
$output = shell_exec($comando);
$output = trim($output);
echo $output;

if($output == "validado"){
    echo "<h1>Bienvenido ${nombre}, eres el de la credencial </h1>";
}else{
    echo "<h1>Alto, tú no eres ${nombre} >:C </h1>";
}


?>