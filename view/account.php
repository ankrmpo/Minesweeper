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
            First name: <input type="text" id="first_name"><br>
            Last name: <input type="text" id="last_name"><br>
            Info: <textarea rows="5" cols="30"></textarea><br>
            <button type="submit" id="save">Save</button>
        </div>
        
        <script>
            $(document).ready(function()
            {
                var info = <?php echo json_encode($data); ?>;
                info = JSON.parse(info);

                
            });
            
        </script>
<?php require_once __DIR__ . '/_footer.php'; ?>