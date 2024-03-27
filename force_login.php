<?php
//			session_start();
//			$_SESSION['user_id'] = 6;
			// Redirect to dashboard
			
			echo $_COOKIE["user_id"];
			setcookie('user_id', 6);
			header("Location: dashboard.php");
			
			?>