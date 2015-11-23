<?php 
class Procedimientos extends ActiveRecord{
	public function eliminar_archivo($id){
		$o = $this->find($id);
		//echo getcwd()."/".$o->url;
		//die(var_dump(file_exists(getcwd()."/".$o->url)));
		if (file_exists(getcwd()."/".$o->url)) {
			if (unlink(getcwd()."/".$o->url)) {
				return true;
			}
			return false;
		}else{
			return false;
		}
	}
}


 ?>