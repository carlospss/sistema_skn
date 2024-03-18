<!--CADASTRO DO ADMIN-->
<?php
	session_start();

	if(isset($_SESSION['admin'])){
		header("location: http://" . $_SERVER['HTTP_HOST'] . "/sistema-skn/admin/painel/");
	}

	if(isset($_POST['password']) && isset($_POST['user'])){

		require_once("../../backend/classes/Login_Admin.php");

		$cadastro = new Login_Admin;

		$cadastro->criar_adm($_POST['user'], $_POST['password']);
	}
?>

<?php require_once("../../frontend/HTML/login.html");?>

<body>
		
		<form method="POST" action class="login">
			<div class="login-screen">
				<div class="app-title">
					<h1>Cadastrar Admin</h1>
				</div>

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

