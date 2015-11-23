<?php 

class ProcedimientosController extends AppController{

	public function index(){
        $prodecimiento = new Procedimientos();
        $this->procedimientos = $prodecimiento->find();
	}

	public function agregar(){

        if (Input::hasPost('oculto')) {  //para saber si se envió el form
            $_FILES['archivo']['name'] = time().$_FILES['archivo']['name'];
            $archivo = Upload::factory('archivo');//llamamos a la libreria y le pasamos el nombre del campo file del formulario
            $archivo->setExtensions(array('rtf')); //le asignamos las extensiones a permitir
            $archivo->setPath(getcwd()."/files/upload/rtf/");
            if ($archivo->isUploaded()) { 
                if ($archivo->save()) {
                    $prodecimiento = new Procedimientos(Input::post("procedimientos"));
                    $prodecimiento->url = "files/upload/rtf/".$_FILES['archivo']['name'];
                    chmod(getcwd()."/files/upload/rtf/".$_FILES['archivo']['name'],0777);
                    
                	if ($prodecimiento->save()) {
                    	Flash::valid('Archivo Grabado en base de datos');
                        Redirect::to("procedimientos/");
                	}else{
                		Flash::error("El Archivo no se pudo grabar en la base de datos");
                	}
                    Redirect::to("procedimientos/");
                }
            }else{
                    Flash::warning('No se ha Podido Subir el Archivo...!!!');
            }
        }
    
	}
    public function editar($id = null){
        if ($id) {
            $prodecimiento = new Procedimientos();
            if (Input::haspost("procedimientos")) {
                $anterior = $prodecimiento->find($id);
                $procedimientos = new Procedimientos(Input::post("procedimientos"));
                if (isset($_FILES['archivo']['name']) and !empty($_FILES['archivo']['name'])) {
                    $_FILES['archivo']['name'] = time().$_FILES['archivo']['name'];
                    $archivo = Upload::factory('archivo');//llamamos a la libreria y le pasamos el nombre del campo file del formulario
                    $archivo->setExtensions(array('rtf')); //le asignamos las extensiones a permitir
                    $archivo->setPath(getcwd()."/files/upload/rtf/");
                    if ($prodecimiento->eliminar_archivo($id)) {
                        Flash::valid("Archivo anterior eliminado correctamente!");
                    }else{
                        Flash::error("El archivo anterior no se pudo eliminar");
                    }
                    if ($archivo->isUploaded()) { 
                        if ($archivo->save()) {
                            chmod(getcwd()."/files/upload/rtf/".$_FILES['archivo']['name'],01777);
                            $procedimientos->url="files/upload/rtf/".$_FILES['archivo']['name'];
                            if ($procedimientos->update()) {
                                Flash::valid("Registro Guardado con éxito");

                            }else{
                                Flash::error("El registro no se guardo con éxito");
                            }
                        }else{
                            Flash::warning("El archivo no se guardo en el servidor");
                        }
                    }else{
                        Flash::warning('No se ha Podido Subir el Archivo...!!!');
                    }                
                }else{
                    $anterior = $prodecimiento->find($id);
                    $procedimientos->url = $anterior->url;
                    if ($procedimientos->update()) {
                        Flash::valid("Registro Guardado con éxito");

                    }else{
                        Flash::error("El registro no se guardo con éxito");
                    }
                }
                Redirect::to("procedimientos/");
            }
           
            $this->procedimientos = $prodecimiento->find($id);
        }else{
            Flash::info("El recurso no existe");
            Redirect::to("/");
        }
    }

    public function eliminar($id){
        if ($id) {
            $prodecimiento = new Procedimientos();
            $this->prodecimiento = $prodecimiento->find($id);
            $url = getcwd()."/".$this->prodecimiento->url;
         
            if ($this->prodecimiento->delete()) {
                if (!file_exists($url) or !unlink($url)) {
                    Flash::error("El archivo no se puedo eliminar del servidor");
                }
                Flash::valid("Archivo Eliminado");
            }else{
                Flash::error("No se elimino el archivo");
            }
            Redirect::to("procedimientos/index");
        }else{
            Flash::info("El recurso no existe");
        }        
            
    }

}

 ?>