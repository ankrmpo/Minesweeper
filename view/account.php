<?php require_once __DIR__ . '/_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="view/style/account.css">
</head>
<body>
    <header>
        <h1>My account</h1>
    </header>
    <a href="choose.php" id="back">Back to menu</a>
    <form action="choose.php?rt=my_account/changeData" method="POST" class="account_info">
        <div id="username" class="bubble">Username: </div>
        <div id="mail" class="bubble">e-mail: </div>
        <div class="bubble">First name: <input type="text" id="first_name" name="first_name"></div>
        <div class="bubble">Last name: <input type="text" id="last_name" name="last_name"></div>
        <div class="bubble">Info: <textarea rows="5" cols="30" id="info" name="info"></textarea></div>
        <button type="submit" class="save">Save</button>
    </form>
    
    <script>
        $(document).ready(function()
        {
            var inf = <?php echo json_encode($data); ?>;

            $("#username").append(inf['username']);
            $("#mail").append(inf['email']);
            $("#first_name").val(inf['first_name']);
            $("#last_name").val(inf['last_name']);
            $("#info").val(inf['info']);
        });
        
    </script>
<?php require_once __DIR__ . '/_footer.php'; ?>