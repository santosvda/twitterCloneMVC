<?php

namespace App\Models; //namepsace é definido atraves do diretorio onde a classe se encontra

use MF\Model\Model;

class Usuario extends Model {
	
	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}

	//salvar

	public function salvar(){

		$query = 'insert into usuarios (nome, email, senha) values (:nome, :email, :senha)';
		$stmt=$this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash32 carac

		$stmt->execute();

		return $this;

	}

	//validar se cadastro pode ser feito
	public function validarCadastro(){
		
		$valido = true;

		if(strlen($this->__get('nome'))< 3){
			$valido = false;
		}

		if(strlen($this->__get('email'))< 3){
			$valido = false;
		}

		if(strlen($this->__get('senha'))< 3){
			$valido = false;
		}

		return $valido;

	}

	//recuperar um usuário pro email
	public function getUsuarioPorEmail(){

		$query = 'select nome, email from usuarios where email = :email';
		$stmt=$this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function autenticar(){

		$query = 'select id,nome,email from usuarios where email = :email and senha = :senha';
		$stmt=$this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if($usuario['id'] != '' && $usuario['nome'] != ''){
			//só uma verificação e setando os valores para o id e nome contidos em $usuario para o obj
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
		} 

		return $this;
	}

	public function getAll(){

		/*vamo la, essa query é fudida
		primeiramente eu busco por todos os usuarios que possuem o nome pesquisado
		só que além disso é utilizado uma sub consulta para cada um dos registros onde é verificado se o usuario da sessão segue ou não
		o usuário do registro atual (se o count é 0 então ele não segue, sendo 1 ele segue(só pode ser 0 e 1 pq não tem como o usuario seguir a mesma pessoa mais de 1x))
		*/

		$query = '
		select 
			u.id,
			u.nome,
			u.email,
			(
				select 
					count(*)
				from
					usuarios_seguidores as us
				where 
					us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
			) as seguindo_sn 
		from 
			usuarios  as u 
		where 
			u.nome like :nome and u.id <> :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

	//informações do usuario
	public function getInfoUsuario(){
		$query = 'select nome from usuarios where id = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//total de twwets
	public function getTotalTweets(){
		$query = 'select count(*) as total_tweet from tweets where id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//total de ususarios que estamos seguindo
	public function getTotalSeguindo(){
		$query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//total de seguidores
	public function getTotalSeguidores(){
		$query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

}

?>