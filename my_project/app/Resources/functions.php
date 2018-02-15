<?php
	function upload($index,$target_dir,$allowed_types = FALSE,$max_size = 2097152){
		$target_file = $target_dir.basename($_FILES[$index]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		//Si le fichier est correctement uploade
		if(!isset($_FILES[$index]) || $_FILES[$index]['error'] > 0) return false;
		//Si fichier existe deja
		if(file_exists($target_file)) return false;
		//Taille limite
		if($_FILES[$index]["size"] > $max_size) return false;
		//Extension
		if($allowed_types !== FALSE && !in_array($imageFileType, $allowed_types)) return false;
		//Deplacement
		return move_uploaded_file($_FILES[$index]["tmp_name"], $target_file);
	}
?>