<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "groupten";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$successMessage = '';
$errors = [];
$email = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        
        // Registration
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Basic validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = "All fields are required.";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = "Email is already registered.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashedPassword);

                if ($stmt->execute()) {
                    // Redirect to welcome page after successful registration
                    header("Location: index.php");
                    exit(); 
                } else {
                    $errors[] = "Error: " . $stmt->error;
                }
            }

            $stmt->close();
        }
    } elseif (isset($_POST['login'])) {
        // Login
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($email) || empty($password)) {
            $errors[] = "Both email and password are required.";
        } else {
            // Prepare SQL to fetch user by email
            $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if user exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $name, $hashedPassword);
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $hashedPassword)) {
                    // Start session and set session variables
                    session_start();
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;

                    // Redirect to a welcome page
                    header("Location:index.php");
                    exit();
                } else {
                    $errors[] = "Incorrect password.";
                }
            } else {
                $errors[] = "No user found with this email.";
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.googleapis.com/css?family=Montserrat:400,800');

* {
    box-sizing: border-box;
}

body {
    background:  #121212; 
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-family: 'Montserrat', sans-serif;
    height: 100vh;
    margin: -20px 0 50px;
}

h1 {
    font-weight: bold;
    margin: 0;
    color: black; 
}


h2 {
    text-align: center;
    color: #3E2C1F; 
	 margin-top: 50px;
}

p {
    font-size: 14px;
    font-weight: 100;
    line-height: 20px;
    letter-spacing: 0.5px;
    margin: 20px 0 30px;
    color: white;
}

nav {
    height: 60px;
    width: 100%;
    background-color:  black; 
    color: #D7B8A3; 
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    display: flex;
    align-items: center; 
}

nav ul {
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    height: 100%;
    width: 100%;
    list-style: none;
}

nav li {
    display: inline;
}

nav .logo {
    flex: 1; 
}

nav .logo img {
    height: 50px; 
    padding: 0 15px; 
}

nav .logo a {
    text-decoration: none; 
}

nav .logo a:hover {
    background-color: transparent; 
    color: inherit; 
}

nav a {
    display: inline-block;
    width: auto; 
    padding: 15px 20px;
    text-align: center;
    color: white;
    font-weight: bold;
}


span {
    font-size: 12px;
    color: #5B4B49; 
}

a {
    color: #5B4B49;
    font-size: 14px;
    text-decoration: none;
    margin: 15px 0;
}

a:hover {
    color: black;
}

button {
    border-radius: 20px;
    border: 1px solid #ff7e5f; 
    background-color: black; 
    color: #FFFFFF;
    font-size: 12px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 80ms ease-in;
}

button:hover {
    background-color: white; 
    color:black;
}

button:active {
    transform: scale(0.95);
}

button:focus {
    outline: none;
}

button.ghost {

    background-color: white; 
    color: #ff7e5f 
}
button.ghost:hover {
    background-color:  #ff7e5f; 
    color: #FFFFFF; 
}
form {
    background-color: #FFFFFF; 
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 50px;
    height: 100%;
    text-align: center;
}

input {
    background-color: #eee;
    border: none;
    padding: 12px 15px;
    margin: 8px 0;
    width: 100%;
}

.container {
    background-color: #ff7e5f;
    border-radius: 10px;
    box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 350px;
}

.form-container {
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
}

.sign-in-container {
    left: 0;
    width: 50%;
    z-index: 2;
}

.container.right-panel-active .sign-in-container {
    transform: translateX(100%);
}

.sign-up-container {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
}

.container.right-panel-active .sign-up-container {
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: show 0.6s;
}

@keyframes show {
    0% {
        opacity: 0;
        z-index: 1;
    }
    20% {
        opacity: 0;
        z-index: 1;
    }
    100% {
        opacity: 1;
        z-index: 5;
    }
}

.overlay-container {
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.6s ease-in-out;
    z-index: 100;
	
}

.container.right-panel-active .overlay-container {
    transform: translateX(-100%);
}

.overlay {
    background: #ff7e5f; 
    color: white; 
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
}
.container.right-panel-active .overlay {
    transform: translateX(50%);
}

.overlay-panel {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    text-align: center;
    top: 0;
    height: 100%;
    width: 50%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
	
}

.overlay-left {
    transform: translateX(-20%);
	
}

.container.right-panel-active .overlay-left {
    transform: translateX(0);
}

.overlay-right {
    right: 0;
    transform: translateX(0);
}

.container.right-panel-active .overlay-right {
    transform: translateX(20%);
}

footer {
    background-color: #3E2C1F; 
    color: #FFFFFF; 
    font-size: 14px;
    bottom: 0;
    position: fixed;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 999;
}

.overlay-left h1,
.overlay-right h1 {
    color: #FFFFFF; 
}


footer p {
    margin: 10px 0;
}

footer i {
    color: #FF4B2B; 
}

footer a {
    color: #8C5E4F; 
    text-decoration: none;
}

</style>
</head>

<body>
    <div id="showcase">
        <header>
            <nav class="cf">
                <ul class="cf">
                    <li class="logo"><a href="welcome.php"><img src="g10.jpg" ></a></li>
                                   
            </nav>
        </header>
    </div>

    <div class="container" id="container">
        
        <!-- Registration Form -->
        <div class="form-container sign-up-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h1>Create Account</h1>
                <span>Use your email for registration</span>
                <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name ?? ''); ?>" />
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" />
                <input type="password" name="password" placeholder="Password" />
                <input type="password" name="confirm_password" placeholder="Confirm Password" />
                <button type="submit" name="register">Sign Up</button>
            </form>
            <?php if ($successMessage): ?>
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>
            <?php if ($errors): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Login Form -->
        <div class="form-container sign-in-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <h1>Log In</h1>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" />
                <input type="password" name="password" placeholder="Password" />
                <a href="#">Forgot your password?</a>
                <button type="submit" name="login">Log In</button>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    
                    <button class="ghost" id="signIn">Log In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>WELCOME TO OUR WEBSITE</h1>
                    <p>Enter your personal details and look at our website.</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>
</body>
</html>