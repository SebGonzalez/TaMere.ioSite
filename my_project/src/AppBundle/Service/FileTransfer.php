<?php

namespace AppBundle\Service;

class FileTransfer{
	function upload($index,$target_dir,$allowed_types = FALSE,$max_size = 2097152){
		$target_dir = $_SERVER['DOCUMENT_ROOT'].$target_dir;
		$target_file = $target_dir.basename($_FILES[$index]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		dump($target_dir);
		//Si le fichier est correctement uploade
		if(!isset($_FILES[$index]) || $_FILES[$index]['error'] > 0){
			trigger_error($_FILES[$index]['error']);
			return false;
		}
		//Si fichier existe deja
		if(file_exists($target_file)){
			trigger_error('File exists');
			return false;
		}
		//Taille limite
		if($_FILES[$index]["size"] > $max_size){
			trigger_error('Max size');
			return false;
		}
		//Extension
		if($allowed_types !== FALSE && in_array($imageFileType, $allowed_types) === FALSE) return false;
		//Deplacement
		return move_uploaded_file($_FILES[$index]["tmp_name"], $target_file);
	}

	function download($path){
		$path_parts = pathinfo($path);
		$filename = $path_parts['basename'];
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/downloads/'.$filename;
		set_time_limit(0);
		$file = @fopen($filepath,"rb");
		while(!feof($file)){
			print(@fread($file, 1024*8));
			ob_flush();
			flush();
		}
	}
}
?>