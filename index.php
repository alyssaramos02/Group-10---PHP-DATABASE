<?php
session_start();

// Array to hold team member data
$team_members = [
    [
        "name" => "Alyssa Andrea C. Ramos",
        "role" => "LEADER",
        "image" => "ramos.png",
        "age" => 23,
        "skills" => "Basic Programming, HTML, CSS, Photo and Video Editing",
        "motto" => "Live everyday as if it was your last.",
        "github" => "https://github.com/alyssaramos02",
        "coursera" => "https://www.coursera.org/user/364687d7c6ca22d8c9086676a9842a1b",
        "linkedin" => "https://www.linkedin.com/in/alyssa-andrea-ramos-3a85b5189"
    ],
    [
        "name" => "John Wengelbert M. Malic",
        "role" => "MEMBER",
        "image" => "malic.png",
        "age" => 22,
        "skills" => "Basic HTML, Basic CSS, Basic c++, Basic MySql",
        "motto" => "Keep moving forward.",
        "github" => "https://github.com/John-Malic",
        "coursera" => "https://www.coursera.org/user/e61b8aea9f4aaba961b3072fd61dcb47",
        "linkedin" => "http://www.linkedin.com/in/john-wengelbert-malic-b346612b3"
    ],
    [
        "name" => "Lanieri M. Moleta",
        "role" => "MEMBER",
        "image" => "moleta.png",
        "age" => 21,
        "skills" => "Programming",
        "motto" => "Live life to the fullest.",
        "github" => "https://github.com/Lmoleta",
        "coursera" => "https://www.coursera.org/user/cf02d1f5ea9f2cfa2f490ef7c19a9df3",
        "linkedin" => "https://www.linkedin.com/in/lanieri-moleta-b4003a323/"
    ]
];

// Initialize variables for form input
$name = $email = $message = "";

// Handle form submission
// USING POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // USING GET in the form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validating the input
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['contact_error'] = "All fields are required.";
    } else {
        
        // Send email (this is just an example, and will only work if your server supports sending emails)
        $to = "your-email@example.com"; 
        $subject = "New Contact Form Submission from $name";
        $body = "Name: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            // Store success message in session
            $_SESSION['contact_success'] = "Thank you for contacting us, $name. We will get back to you soon.";
        }

        // Store submitted data in session for use in JavaScript
        $_SESSION['submitted_data'] = json_encode([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Profile of Group 10</title>
    <style>
        body, ul {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif; 
            color: #fff;
        }

        body {
            background-color: #121212; 
        }

        header {
    display: flex;
    justify-content: space-between; /
    align-items: center; 
    background-color: #000;
    padding: 10px 20px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

        h1 {
            font-size: 36px;
            text-transform: uppercase;
            margin-top: 150px;
            color: #ff7e5f; 
            letter-spacing: 2px;
        }

        hr {
            border: 1px solid #333;
            width: 50%;
        }

        .logo img {
            width: 150px; 
            height: auto; 
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .column {
            flex: 1;
            max-width: 35%; 
            margin: 16px;
            padding: 2px 10px;
            box-sizing: border-box;
        }

        .card { 
            background-color: #1c1c1c;
            border: 1px solid #333; 
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
            flex: 1;
            min-height: 450px; 
            align-items: stretch; 
        }

        .container {
            flex: 1;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%; 
        }

        .card:hover {
            transform: translateY(-10px);
        }

        @media screen and (max-width: 670px) { 
            .column { 
                max-width: 100%;
            } 
        }

        .button { 
            border: none; 
            padding: 10px 15px; 
            color: white; 
            background-color: #ff7e5f; 
            text-align: center; 
            cursor: pointer; 
            width: 100%; 
            margin-bottom: 10px; 
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.3s ease;
        } 

        .button:hover { 
            background-color: #e76e51; 
        } 

        .profile-image-container {
            display: flex; 
            justify-content: center;
            align-items: center; 
            height: 200px;
        }

        .profile-image {
            width: 300px; 
            height: 300px; 
            object-fit: cover; 
        }

        footer {
            padding: 10px 0;
            width: 100%;
            background-color: #000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .nav-links a {
            color: black; 
            text-decoration: none; 
            font-weight: bold;
            transition: color 0.3s, text-decoration 0.3s; 
        }

        .nav-links a:hover {
            text-decoration: underline; 
            color: #ff7e5f; 
        }

        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 500px;
            position: relative; 
            top: 50%;
            transform: translateY(-50%); 
            color: black;
            box-sizing: border-box; 
        }

        #modalName {
            color: #ff7e5f; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: red;
            text-decoration: none;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px; 
        }

        .contact-form input,
        .contact-form textarea {
            width: 90%;
            max-width: 300px;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #1c1c1c; 
            color: #fff; 
        }
        .nav-container {
    display: flex; 
    align-items: center; 
    margin-left: 0;
    padding-right: 20px; 
}
        .contact-button {
        float: right; 
        background-color: #ff5733; 
        color: white;
        border: none;
        padding: 10px 40px;
        cursor: pointer;
        font-size: 16px;
        margin-right: 20px;
        border-radius: 10px; 
        transition: background-color 0.3s ease;
    }

    .contact-button:hover {
        background-color: #e74c3c; 
    }

    .send-button {
    background-color: #4CAF50; 
    color: white; 
    border: none; 
    padding: 12px 20px; 
    text-align: center;
    text-decoration: none; 
    display: inline-block; 
    font-size: 16px; 
    margin: 10px 0; 
    cursor: pointer; 
    border-radius: 5px; 
    transition: background-color 0.3s ease; 
}

.send-button:hover {
    background-color: #45a049; 
}

.search-container {
     margin-top: 20px;
}

.search-input {
    padding: 10px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #1c1c1c;
    color: #fff;
}
</style>


</head>

<body>
<header>
        <div class="logo">
            <a href="new.php">
                <img src="g10.jpg" alt="Logo">
            </a>
        </div>
        <div class="nav-container"> 
            <button class="contact-button" onclick="openContactModal()">Contact Us</button>
            <button class="contact-button" onclick="window.location.href='welcome.php';">Log Out</button>
        </div>
    </header>

    <main>
        <center>
            <h1>Team Profile</h1>

            <hr>
                <div class="search-container">
                    <input type="text" id="searchInput" class="search-input" placeholder="Search team members...">
                </div>

            <div id="teamMembers" class="row">
            <?php
            foreach ($team_members as $index => $member) {
                if ($index % 3 == 0) {
                    echo '<div class="row">';
                }

                echo '
                <div class="column">
                    <div class="card">
                        <div class="profile-image-container">
                            <img src="images/' . htmlspecialchars($member["image"]) . '" alt="' . htmlspecialchars($member["name"]) . '" class="profile-image">
                        </div>
                        <div class="container">
                            <h2>' . htmlspecialchars($member["name"]) . '</h2>
                            <p>' . htmlspecialchars($member["role"]) . '</p>
                            <button class="button" onclick=\'openModal(' . htmlspecialchars(json_encode($member, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)) . ');\' >View More</button>
                        </div>
                    </div>
                </div>';

                if ($index % 3 == 2 || $index == count($team_members) - 1) {
                    echo '</div>';
                }
            }
            ?>
            </div>
        </center>
    </main>

<!-- Contact Us Modal -->
<div id="contactModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeContactModal()">&times;</span>
            <center>
            <h3>Contact Us</h3>
            <form action="" method="POST" class="contact-form">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" rows="4" required></textarea>
                <button type="submit" class="send-button">Send Message</button>
            </form>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($name) && !empty($email) && !empty($message)): ?>
                <h3>Your Recent Input:</h3>
                <p>Name: <?php echo htmlspecialchars($name); ?></p>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                <p>Message: <?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['contact_error'])): ?>
                <p style="color: red;"><?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></p>
            <?php endif; ?>
            <?php if (isset($_SESSION['contact_success'])): ?>
                <p style="color: green;"><?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeConfirmationModal()">&times;</span>
            <center>
                <h3>Submission Successful!</h3>
                <p>Your Name: <span id="confirmName"></span></p>
                <p>Your Email: <span id="confirmEmail"></span></p>
                <p>Your Message: <span id="confirmMessage"></span></p>
            </center>
        </div>
    </div>


    <!-- Profile Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="profile-container">
                <center>
                    <h2 id="modalName"></h2>
                    <p><strong>Position:</strong> <span id="modalRole"></span></p>
                    <p><strong>Age:</strong> <span id="modalAge"></span></p>
                    <p><strong>Skills:</strong> <span id="modalSkills"></span></p>
                    <p><strong>Motto:</strong> <i><span id="modalMotto"></span></i></p>
                    <nav class="nav-links">
                        <a id="modalGithub" href="" target="_blank">GitHub</a> |
                        <a id="modalCoursera" href="" target="_blank">Coursera</a> |
                        <a id="modalLinkedin" href="" target="_blank">LinkedIn</a>
                    </nav>
                </center>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Team Profile</p>
    </footer>

    <script>

        function createMemberCard(member) {
                    return `
                        <div class="column">
                            <div class="card">
                                <div class="profile-image-container">
                                    <img src="images/${member.image}" alt="${member.name}" class="profile-image">
                                </div>
                                <div class="container">
                                    <h2>${member.name}</h2>
                                    <p>${member.role}</p>
                                    <button class="button" onclick='openModal(${JSON.stringify(member)})'>View More</button>
                                </div>
                            </div>
                        </div>
                    `;
                }
        
         // AJAX Live Search
         document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filteredMembers = <?php echo json_encode($team_members); ?>.filter(member => {
                return member.name.toLowerCase().includes(query);
            });

            const teamMembersDiv = document.getElementById('teamMembers');
            teamMembersDiv.innerHTML = '';
            filteredMembers.forEach(member => {
                teamMembersDiv.innerHTML += createMemberCard(member);
            });
        });

       // JavaScript to handle modal
        function openModal(member) {
            document.getElementById('modalName').innerText = member.name;
            document.getElementById('modalRole').innerText = member.role;
            document.getElementById('modalAge').innerText = member.age;
            document.getElementById('modalSkills').innerText = member.skills;
            document.getElementById('modalMotto').innerText = member.motto;
            document.getElementById('modalGithub').href = member.github;
            document.getElementById('modalCoursera').href = member.coursera;
            document.getElementById('modalLinkedin').href = member.linkedin;
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }
        function openContactModal() {
            document.getElementById('contactModal').style.display = 'block';
        }
        function closeContactModal() {
            document.getElementById('contactModal').style.display = 'none';
        }
        function closeConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }
        

    // Display confirmation modal after form submission
    <?php if (isset($_SESSION['submitted_data'])): ?>
            const submittedData = <?php echo $_SESSION['submitted_data']; ?>;
            document.getElementById('confirmName').innerText = submittedData.name;
            document.getElementById('confirmEmail').innerText = submittedData.email;
            document.getElementById('confirmMessage').innerText = submittedData.message;
            document.getElementById('confirmationModal').style.display = 'block';
            <?php unset($_SESSION['submitted_data']);  ?>
        <?php endif; ?>
</script>
</body>
</html>