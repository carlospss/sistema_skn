<!--LOGIN DO CLIENTE-->
<?php
	session_start();

	if(isset($_SESSION['id'])){
		header("location: http://" . $_SERVER['HTTP_HOST'] . "/sistema-skn/acompanhe-seu-processo/");
	}

	

	if(isset($_POST['password']) && isset($_POST['user'])){

		require_once("../backend/classes/Login_Cliente.php");

		$login_cliente = new Login_cliente($_POST['user'], $_POST['password']);

		$erro = $login_cliente->fazer_login();
		echo $erro;

	}
?>

	<?php require_once("../frontend/HTML/login.html");?>

	<body>
		
		<form method="POST" action class="login">
			<div class="login-screen">
				<div class="app-title">
					<h1>Login cliente</h1>
				</div>
				<img src="logo.jpeg">
				<div class="login-form">
					<div class="control-group">
						<input type="text" class="login-field" value="" name="user" placeholder="Usuário" id="login-name" required>
						<label class="login-field-icon fui-user" for="login-name" ></label>
					</div>

					<div class="control-group">
						<input type="password" class="login-field" value="" placeholder="senha" id="login-pass" required name="password">
						<label class="login-field-icon fui-lock" for="login-pass"></label>
					</div>

					<button class="btn btn-primary btn-large btn-block" type="submit">login</button>
				</div>
			</div>
		</form>
	</body>