<?php


// // Making a login system using session and redurecting
// // the user to  dashboard.php after successfull login.
// session_start();

// require_once 'config.php';

// $email = $emailErr = $passwordErr = $loginErr = '';

// if($_SERVER['REQUEST_METHOD'] === 'POST'){
//     $error = false;
//     $email = trim($_POST['email'] ?? '');
//     $password = $_POST['password'] ?? '';

//     if(empty($email)){
//         $emailErr = "Email is required";
//        $error = true;
    
//     } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
//         $emailErr = "Enter a Valid Email";
//         $error = true;
//     } 
//     if(empty($password)){
//         $passwordErr = "Password  is required";
//         $error = true;
//     }
//     if($error === false){
//         // This is a prepared sql query that searches for a row 
//         // where column is matched with entered email and if founds
//         // it returns the id name and password.
//         $sql = "SELECT id, name, password FROM users WHERE email = ?";
//         // prepares the sql.
//         $stmt = mysqli_prepare($conn, $sql);
//         if($stmt){
//             // Binds the data. 
//             mysqli_stmt_bind_param($stmt, "s", $email );

//             // execute the prepared query or statement.
//             mysqli_stmt_execute($stmt);

//             // This returns a result set.
//             $result = mysqli_stmt_get_result($stmt);

//             // It fetches the single row from the result set
//             // as an associative array eg: id => 1, name => jainy.   
                //If no user exists it returns false.
//             if($user = mysqli_fetch_assoc($result)){
//                 if(password_verify($password, $user['password'])){
//                     $_SESSION['user_id'] = $user['id'];
//                     $_SESSION['user_name'] = $user['name'];
//                     header("Location: dashboard.php");
//                 } else{
//                     $passwordErr = "incorrect password";
//                 }
//             } else{
//                 $loginErr = "No user exists with this email.";
//             }
//         } else{
//             die("failed to prepare sql" . mysqli_error($conn));
//     }
//     mysqli_stmt_close($stmt);
//  }
// }


session_start();

$email = $emailErr = $passwordErr = "";
require_once 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){ 

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error = false;

    if(empty($email)){
        $emailErr = "Email is required";
        $error = true;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailErr = "Enter a valid email";
        $error = true;
    }
    if(empty($password)){
        $passwordErr = "Password is required";
        $error = true;
    }
    if(!$error){
        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){ 
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)){
            if(password_verify($password, $row['password'])){ 
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            
            if(!empty($_POST['remember'])){
                setcookie("user_id", $row['id'], time() + (86400 * 7), "/" );
                setcookie("user_name", $row['name'], time() + (86400 * 7), "/" );
            }
            header("location: dashboard.php");
        } else{
            $passwordErr = "Incorrect Password";
        }
        } else{
            $emailErr = "Invalid Email";
        }
    }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

          body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            min-height: 100vh;            
            align-items: center;
            justify-content: center;
        }

        .login-container{
            background: white;
            padding: 30px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
        }
        .signup-header{
            text-align: center;
            margin-bottom: 30px;
        }

        
        .signup-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .signup-header p {
            color: #666;
            font-size: 14px;
        }

        .error-text{
            display: block;
        }

       
        .form-group{
            margin-bottom: 20px
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .form-group input{
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

         .checkbox-group{
            margin-bottom: 20px;
            align-items: center;
            display: flex;
            gap: 8px;
            
        } 

        .checkbox-group label{
           order: 1;
           margin: 0;
        }

        .checkbox-group input[type="checkbox"] {
            order: 0;
            margin: 0;
        
            width: 16px;
            height: 16px;
            accent-color: #667eea; /* Changes checkbox color to match theme */
            cursor: pointer;
         
        }

        .message{
            text-align:center;
        }

        .login-btn{
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        
    </style>
    
</head>


<body>

<div class="login-container">

<div class="signup-header">
    <h2>Login</h2>
    <p>To Continue</p>
    </div>
    <form method="post" action="" >
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
        <span class="error-text" style="color:red;"><?= $emailErr ?></span>
    </div>

        <div class="form-group">
            <label for="password">Password</label>
           <input type="password" name="password" id="password" >
        <span class="error-text" style="color:red;"><?= $passwordErr ?></span>
    </div>
    <div class="checkbox-group" >
        <input type="checkbox" name="remember" id="checkbox">
        <label for="checkbox">Remember Me</label>
    </div>
        <button type="submit" class="login-btn">Login</button> <br><br>


    </form>

<!-- 
 <php if ($loginErr): ?>
        <h4 class="message" style="color:red;"></?= htmlspecialchars($loginErr) ?></h3>
    <php endif; ?> -->
    </div>
     
</body>
</html>
