<?php
$name = $email = $password = $confirmPass = '';
$nameErr = $emailErr = $passwordErr = $confirmErr ='';
$error = false;
$success = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require_once 'config.php';
    $name = trim(filter_input(INPUT_POST, 'name' , FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);

     if(empty($name)){
        $nameErr = "Name is required";
        $error = true;
    } elseif(strlen($name) < 2 || strlen($name) > 50){
        $nameErr = "Enter the name between 2 to 50 characters"; 
        $error = true;
    } elseif(is_numeric($name)){
        $nameErr =  "Name cannot only contain numbers";
        $error = true;
    } elseif(!preg_match('/^[A-Za-z0-9\s]+$/' , $name)){
        $nameErr = "Name contain Invalid Characters";
        $error = true;    
    }

    if(empty($email)){
        $emailErr = "Email is required";
        $error = true;
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailErr = "Enter a valid Email";
        $error = true;
    }

   
    if (empty($password)) {
        $passwordErr = "Password is required.";
        $error = true;
    } elseif (strlen($password) < 6) {
        $passwordErr = "Password must be at least 6 characters.";
        $error = true;
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $passwordErr = "Include at least one uppercase letter.";
        $error = true;
    } elseif (!preg_match('/[a-z]/', $password)) {
        $passwordErr = "Include at least one lowercase letter.";
        $error = true;
    } elseif (!preg_match('/[0-9]/', $password)) {
        $passwordErr = "Include at least one number.";
        $error = true;
    } elseif (preg_match('/\s/', $password)) {
        $passwordErr = "Password can not contain spaces.";
        $error = true;
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)){
        $passwordErr = "Include at least one special character.";
        $error = true;
    }

    if(empty($confirmPass)){
        $confirmErr = "Confirm your Password";
        $error = true;
    } elseif($password !== $confirmPass){
        $confirmErr = "Passwords do not match";
        $error = true;
    } 

    if(!$error){



        $checkSql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $checkStmt = mysqli_prepare($conn, $checkSql);
        
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "s", $email);      
            mysqli_stmt_execute($checkStmt);                      
            mysqli_stmt_bind_result($checkStmt, $count);   
            mysqli_stmt_fetch($checkStmt);                 
        
            if ($count > 0) {
                $emailErr = "Email already exists";
                $error = true;
            }
            mysqli_stmt_close($checkStmt);
        }
        
        if(!$error){ 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt, "sss" , $name, $email, $hashedPassword);
            if(mysqli_stmt_execute($stmt)){
                $success = true;
            } else{
                die("Failed to add a user");
            }
        
            $name = $email = $password = $confirmPass = '';
        } else{
            die('failed to prepare the query ');
        }
        mysqli_stmt_close($stmt);
        
    }
    
 }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signup-header {
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

    
        .success-message {
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            animation: slideDown 0.5s ease-out;
        }

        .success-message .icon {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }

        .success-message h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .success-message p {
            font-size: 14px;
            opacity: 0.9;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        
        .error-text {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
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

        .form-group input.error {
            border-color: #e74c3c;
        }

        
        .password-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-container input {
            padding-right: 45px;
        }

        .eye-button {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #888;
            font-size: 18px;
            padding: 5px;
            border-radius: 4px;
            transition: color 0.3s ease, background-color 0.3s ease;
            z-index: 10;
        }

        .eye-button:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .eye-icon {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .password-requirements {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .checkbox-group label {
            font-size: 14px;
            color: #666;
            margin-bottom: 0;
        }

        .signup-btn {
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

        .signup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .new-account-btn {
            background: linear-gradient(135deg, #28a745, #20c997);
            margin-top: 15px;
        }

        .new-account-btn:hover {
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        @media (max-width: 480px) {
            .signup-container {
                padding: 30px 20px;
            }
            
            .signup-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <?php if($success): ?>
            <div class="success-message">
                <span class="icon">✓</span>
                <h3>Account Created Successfully!</h3>
                <p>Welcome aboard! Your account has been created and you can now start using our services.</p>
            </div>
            <button onclick="location.reload()" class="signup-btn new-account-btn">Create Another Account</button>
            <div class="login-link">
                <a href="login.php">Go to Login</a>
            </div>
        <?php else: ?>
            <div class="signup-header">
                <h2>Create Account</h2>
                <p>Join us today and get started</p>
            </div>
            
            <form action="" method="post" id="signupForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" 
                           class="<?= !empty($nameErr) ? 'error' : '' ?>" >
                    <?php if(!empty($nameErr)): ?>
                        <span class="error-text"><?= $nameErr ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" 
                           class="<?= !empty($emailErr) ? 'error' : '' ?>">
                    <?php if(!empty($emailErr)): ?>
                        <span class="error-text"><?= $emailErr ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-container">
                        <input type="password" id="password" name="password" value="<?= htmlspecialchars($password) ?>" 
                               class="<?= !empty($passwordErr) ? 'error' : '' ?>" >
                        <button type="button" class="eye-button" onclick="togglePassword('password')" title="Show/Hide Password">
                        <svg class="eye-icon" id="eye-icon-password" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                        </button>
                    </div>
                    <div class="password-requirements">
                        Must include: uppercase, lowercase, number, special character (min 6 chars)
                    </div>
                    <?php if(!empty($passwordErr)): ?>
                        <span class="error-text"><?= $passwordErr ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirmPass">Confirm Password</label>
                    <div class="password-input-container">
                        <input type="password" id="confirmPass" name="confirmPass" value="<?= htmlspecialchars($confirmPass) ?>" 
                               class="<?= !empty($confirmErr) ? 'error' : '' ?>" >
                        <button type="button" class="eye-button" onclick="togglePassword('confirmPass')" title="Show/Hide Password">
                        <svg class="eye-icon" id="eye-icon-confirmPass" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                    <?php if(!empty($confirmErr)): ?>
                        <span class="error-text"><?= $confirmErr ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to the Terms of Service and Privacy Policy</label>
                </div>
                
                <button type="submit" class="signup-btn">Create Account</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Sign in here</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-icon-' + inputId);
            const eyeButton = eyeIcon.parentElement;
            
            if (passwordInput.type === 'password') {
                // Show password - change to eye-closed icon
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/>';
                eyeButton.title = 'Hide Password';
            } else {
                // Hide password - change to eye-open icon
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
                eyeButton.title = 'Show Password';
            }
        }
    </script>
</body>
</html>