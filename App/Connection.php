<?php
	
	namespace App;

	class Connection {

		//define o metodo com estatico assim não precisa criar classe de conexão
		public static function getDb(){

			try{
				// \PDO indica que a classe fica no namespace raiz ao inves App
				$conn = new \PDO(
					"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
					"root",
					""
				);

				return $conn;

			} catch(\PDOException $e) {
				//tratar o erro
				echo "Erro na conexão DB";
			}
		} 
	}

?>