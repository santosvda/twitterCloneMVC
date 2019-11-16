<?php
		
		namespace App\Controllers;

		//recursos miniframework
		use MF\Controller\Action;
		use MF\Model\Container;

		class AppController extends Action{

			public function timeline(){

				$this->validaAutenticacao();

				//recuperação dos tweets
				$tweet = Container::getModel('Tweet');
				$tweet->__set('id_usuario', $_SESSION['id']);
				$tweets = $tweet->getAll();

				$this->view->tweets = $tweets;

				//preenchendo informações sobre ousuario
				$usuario = Container::getModel('Usuario');
				$usuario->__set('id', $_SESSION['id']);
				
				$this->view->info_usuario = $usuario->getInfoUsuario();
				$this->view->total_tweets = $usuario->getTotalTweets();
				$this->view->total_seguindo = $usuario->getTotalSeguindo();
				$this->view->total_seguidores = $usuario->getTotalSeguidores();


				$this->render('/timeline');
			}

			public function tweet(){

					
				$this->validaAutenticacao();

				
					
				$tweet = Container::getmodel('Tweet');

				$tweet->__set('tweet', $_POST['tweet']);
				$tweet->__set('id_usuario', $_SESSION['id']);

				$tweet->salvar();

				header('Location: /timeline');

			}

			public function validaAutenticacao() {
				session_start();

				//se não tiver nada na super global então o usuario nas esta conectado, sendo assim é redirecionado pra index
				if (!isset($_SESSION['id']) || $_SESSION['id'] == '' && !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
					header('Location: /?login=erro');	
				}
			}

			public function quemSeguir(){

				$this->validaAutenticacao();

				/*echo "<br<br>><pre>";
				print_r($_GET);
				echo "</pre>";*/

				$usuario = Container::getModel('Usuario');
				$usuario->__set('id', $_SESSION['id']);
				
				$this->view->info_usuario = $usuario->getInfoUsuario();
				$this->view->total_tweets = $usuario->getTotalTweets();
				$this->view->total_seguindo = $usuario->getTotalSeguindo();
				$this->view->total_seguidores = $usuario->getTotalSeguidores();


				$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

				$usuarios = [];


				if ($pesquisarPor != '') {
					//echo '<br><br>Pesquisando por: '.$pesquisarPor;
					
					$usuario = Container::getModel('Usuario');
					$usuario->__set('nome', $pesquisarPor);
					$usuario->__set('id', $_SESSION['id']);
					$usuarios = $usuario->getAll();

				}

				$this->view->usuarios = $usuarios;

				$this->render('quemSeguir');

			}

			public function acao(){

				$this->validaAutenticacao();

				//acao
				$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
				//id_usuario que será seguido
				$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

				$usuarioSeguidor = Container::getmodel('UsuarioSeguidor');
				$usuarioSeguidor->__set('id_usuario', $_SESSION['id']);
				$usuarioSeguidor->__set('id_usuario_seguindo', $id_usuario_seguindo);

				if ($acao == 'seguir') {
					
					$usuarioSeguidor->seguirUsuario();

				} else if ($acao == 'deixar_de_seguir'){
					
					$usuarioSeguidor->deixarSeguirUsuario();
				}

				$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

				header("Location: /quem_seguir?pesquisarPor=".$pesquisarPor);

			}

			public function remover(){

				$this->validaAutenticacao();

				$id_tweet = $_POST['id_tweet'];

				$tweet = Container::getModel('Tweet');
				$tweet->__set('id', $id_tweet);
				$tweet->remove();

				header('Location: /timeline');
			}

		}
?>