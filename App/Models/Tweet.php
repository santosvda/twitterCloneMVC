<?php

namespace App\Models; //namepsace é definido atraves do diretorio onde a classe se encontra

use MF\Model\Model;

class Tweet extends Model {
	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}

	//salvar
	public function salvar(){

		$query = 'insert into tweets(id_usuario,tweet) values(:id_usuario, :tweet)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':tweet', $this->__get('tweet'));
		$stmt->execute();

		return $this;
	}

	//recuperar

	public function getAll() {

		/*Beleza, essa query é o seguinte
		retornar todos os tweets baseadas em duas condições
		1- se o tweet for do usuario logado
		2- se o tweet for de alguem qeu o usuario logado segue
		*/

		$query = "
		select 
			t.id,t.id_usuario,u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
		from 
			tweets as t
			left join usuarios as u on (t.id_usuario=u.id)
		where 
			id_usuario = :id_usuario
			or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
		order by
			t.data desc";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

	public function remove(){

		$query = 'delete from tweets where id = :id';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();

		return $this;
	}

}

?>