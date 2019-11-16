<?php

	namespace MF\Controller;

	abstract class Action {

		protected $view;

		public function __construct(){
			$this->view = new \stdClass();
		}

		//renderiza a view
		protected function render($view, $layout = 'layout'){
			$this->view->page = $view;

			//verifica se parametro passado corresponde a um arquvivo
			if(file_exists("../App/Views/".$layout.".phtml")){
				require_once "../App/Views/".$layout.".phtml";
			}
			else{
				//error 404 etc
				$this->content();
			}
			
		}

		//metodo recebe por parametro qual a view para então realizar o require
		protected function content(){
				$classeAtual =  get_class($this);

				$classeAtual = str_replace('App\\Controllers\\', '', $classeAtual);

				$classeAtual = strtolower(str_replace('Controller', '', $classeAtual));

				require_once "../App/Views/".$classeAtual."/".$this->view->page.".phtml";
		}
	}
?>