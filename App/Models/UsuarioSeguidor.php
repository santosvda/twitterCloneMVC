<?php

namespace App\Models; //namepsace é definido atraves do diretorio onde a classe se encontra

use MF\Model\Model;

class UsuarioSeguidor extends Model {

	private $id;
	private $id_usuario;
	private $id_usuario_seguindo;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}

	public function seguirUsuario(){
		
		$query = 'insert into usuarios_seguidores (id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
		$stmt->execute();

		return true;

	}

	public function deixarSeguirUsuario(){
		
		$query = 'delete from usuarios_seguidores where id_usuario = :id_usuario 
		and id_usuario_seguindo = :id_usuario_seguindo';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
		$stmt->execute();

		return true;
	}

}

?>