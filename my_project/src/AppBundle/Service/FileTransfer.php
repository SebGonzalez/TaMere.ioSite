<?php

namespace AppBundle\Service;

class FileTransfer{
	private $web_directory_path;
	//On construit le service de transfert de fichier avec le chemin vers le repertoire path
	function __construct($web_directory_path){
		$this->$web_directory_path = $web_directory_path;
	}
	//Fonction qui gere l upload de fichier vers le serveur
	function upload($index,$target_dir,$allowed_types = FALSE,$max_size = 2097152){
		$target_dir = $this->web_directory_path.$target_dir;
		$target_file = $target_dir.basename($_FILES[$index]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		//On verifie si le fichier est correctement uploade
		if(!isset($_FILES[$index]) || $_FILES[$index]['error'] > 0){
			trigger_error($_FILES[$index]['error']);
			return false;
		}
		//On verifie si le fichier existe deja
		if(file_exists($target_file)){
			trigger_error('File exists');
			return false;
		}
		//On verifie la taille limite du fichier
		if($_FILES[$index]["size"] > $max_size){
			trigger_error('Max size');
			return false;
		}
		//On verifie les extensions du fichier
		if($allowed_types !== FALSE && in_array($imageFileType, $allowed_types) === FALSE) return false;
		//On deplace le fichier vers sa destination finale
		return move_uploaded_file($_FILES[$index]["tmp_name"], $target_file);
	}
	//TO DO Fonction inutile pour l instant
	function download($path){
		$path_parts = pathinfo($path);
		$filename = $path_parts['basename'];
		$filepath = $this->web_directory_path.'downloads/'.$filename;
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