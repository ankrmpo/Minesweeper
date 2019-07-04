<?php require_once __DIR__ . '/_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="view/style/account.css">
</head>
<body>
    <header>
        <h1>My account</h1>
    </header>
    <body>
        <div class="account_info">
            <div class="username">Username: </div>
            <div class="mail">e-mail: </div>
            First name: <input type="text" class="first_name"><br>
            Last name: <input type="text" class="last_name"><br>
            Info: <textarea rows="5" cols="30" class="info"></textarea><br>
            <button type="submit" id="save">Save</button>
        </div>
        
        <script>
            $(document).ready(function()
            {
                var information = <?php echo json_encode($data); ?>;
                information = JSON.parse(info);

                $(information['username']).appendTo( ".username" );
                $(information['email']).appendTo(".mail");
                $(".first_name").val(information['first_name']);
                $(".last_name").val(information['last_name']);
                $(".info").val(information['info']);
            });
            
        </script>
<?php require_once __DIR__ . '/_footer.php'; ?>