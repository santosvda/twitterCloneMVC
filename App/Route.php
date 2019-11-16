<?php
	
	namespace App;

	use MF\Init\Bootstrap;

	class Route extends Bootstrap{

		//OBS: as rotas definem qual o controlador que será acionado e a ação dentro do controlador
		//definindo as rotas que a aplicação posix_setuid(uid)
		protected function initRoutes(){

			$routes['home'] = array(
				'route' => '/',
				'controller' => 'indexController',
				'action' => 'index'
			);

			$routes['inscreverse'] = array(
				'route' => '/inscreverse',
				'controller' => 'indexController',
				'action' => 'inscreverse'
			);

			$routes['registrar'] = array(
				'route' => '/registrar',
				'controller' => 'indexController',
				'action' => 'registrar'
			);

			$routes['autenticar'] = array(
				'route' => '/autenticar',
				'controller' => 'AuthController',
				'action' => 'autenticar'
			);

			$routes['timeline'] = array(
				'route' => '/timeline',
				'controller' => 'AppController',
				'action' => 'timeline'
			);

			$routes['sair'] = array(
				'route' => '/sair',
				'controller' => 'AuthController',
				'action' => 'sair'
			);

			$routes['tweet'] = array(
				'route' => '/tweet',
				'controller' => 'AppController',
				'action' => 'tweet'
			);

			$routes['quem_seguir'] = array(
				'route' => '/quem_seguir',
				'controller' => 'AppController',
				'action' => 'quemSeguir'
			);

			$routes['acao'] = array(
				'route' => '/acao',
				'controller' => 'AppController',
				'action' => 'acao'
			);

			$routes['remover'] = array(
				'route' => '/remover',
				'controller' => 'AppController',
				'action' => 'remover'
			);


			$this->setRoutes($routes);
		}

	}

?>