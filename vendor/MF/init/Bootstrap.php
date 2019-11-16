<?php
	namespace MF\Init;
	//classe abstrata não pode ser instanciada apenas herdada
	abstract class Bootstrap{

		private $routes;
		//assinatura desse metodo trata-se de um metodo abstrato
		abstract protected function initRoutes();//classe herdada tem que ter esse metodo

		public function __construct(){
			$this->initRoutes();
			$this->run($this->getUrl());
		}

		public function getRoutes(){
			return $this->routes;
		}

		public function setRoutes(array $routes){
			$this->routes = $routes;
		}

		protected function run($url) {
			foreach ($this->getRoutes() as $key => $route) {
				if ($url == $route['route']) {
					$class = "App\\Controllers\\".ucfirst($route['controller']);
					#EX: App\Controllers\IndexController;

					$controller = new $class;

					$action = $route['action'];

					$controller->$action();
				}
			}
		}

		protected function getUrl(){
			//S_SERVER retorna todos os detalhes od servidor da aplicação
			//parse_url função recebe a url, interpreta a url e retorna os componentes 
			//PHP_URL_PATH contante que quando submetida ao parse_url faz que o retorno seja a string relativa ao path - do contrario retornar uma array com o path e parametros
			return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		}
	}
?>