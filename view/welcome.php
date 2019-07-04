<?php require_once __DIR__ . '/_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="view/style/welcome.css">
</head>
<body>
    <header>
        <h1>Welcome to <br><br><span>Minesweeper multiplayer!</span></h1>
        <br>
        <h2>Join us, it's a bomb ;)</h2>
    </header>
    <br>

    <button class="collapsible" id="Login">Login</button>
    <div class="content">
    <form action="index.php" method="post">
        <label>Username: </label><input type="text" name="username" id="username">
        <br>
        <br>
        <label>Password: </label><input type="password" name="password" id="password">
        <br>
        <br>
        <button class="confirm" type="submit" name="login">Confirm</button>
    </form>
    </div>

    <button class="collapsible" id="Register">Register</button>
    <div class="content">
    <form action="index.php" method="post">
        <label>Username: </label><input type="text" name="username" id="username">
        <br>
        <br>
        <label>Password: </label><input type="password" name="password" id="password">
        <br>
        <br>
        <label>E-mail: </label><input type="text" name="mail" id="mail">
        <br>
        <br>
        <button class="confirm" type="submit" name="login">Confirm</button>
    </form>
    </div>

    <script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) 
    {
        coll[i].addEventListener("click", function() 
        {
            for(var j = 0; j < coll.length; j++)
            {
                var con = coll[j].nextElementSibling;
                con.style.display = "none";
            }

            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") content.style.display = "none";
            else content.style.display = "block";
        });
    }
    </script>

<?php require_once __DIR__ . '/_footer.php'; ?>