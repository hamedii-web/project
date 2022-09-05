<?php

namespace Admin;

use Auth\Auth;

class Admin{

	function __construct() {
		$auth = new Auth();
		$auth->checkAdmin();
		
		$this->currentDomain = CURRENT_DOMAIN;
		$this->basePath = BASE_PATH;
	}

	protected function redirect($url){
		header("Location: ". trim($this->currentDomain , '/ ') . '/' . trim($url , '/ '));
		exit();
	}

	protected function redirectBack(){
		header("Location:" . $_SERVER['HTTP_REFERER']);
		exit();
	}

	protected function imageSave($image , $imagePath , $imageName = null){
		if ($imageName){

			$extention = explode('/' , $image['type'])[1];
			$imageName = $imageName . '.' . $extention;
		}
		else{
			$extention = explode('/' , $image['type'])[1];
			$imageName = date("Y-m-d-H-i-s") . '.' . $extention;
		}

		$imageTemp = $image['tmp_name'];
		$imagePath = 'public/' . $imagePath . '/';
		if (is_uploaded_file($imageTemp)){

			if (move_uploaded_file($imageTemp , $imagePath . $imageName)){

				return $imagePath . $imageName;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	protected function removeImage($path){
		$path =  trim($path , '/ ');

		if (file_exists($path)){
			unlink($path);
		}

	}
}
