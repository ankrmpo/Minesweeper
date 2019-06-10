<?php require_once __DIR__ . '/_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="view/style/hello.css">
</head>
<body>
    <header>
        <h1>Hello,</h1>
    </header>

    <div class="menu">
        <div class="tooltip1"><a href="choose.php?rt=my_account/index">My account</a><p class="tooltiptext1">Information about your account</p></div><br>
        <div class="tooltip2"><a href="choose.php?rt=start_game/index">Join a game</a><p class="tooltiptext2">Start a new game or join one!</p></div><br>
        <div class="tooltip3"><a href="choose.php?rt=ranking/index">Ranking</a><p class="tooltiptext3">See how you stand among other players</p></div><br>
        <div class="tooltip4"><a href="choose.php?rt=logout/index">Logout</a><p class="tooltiptext4">See you later, alligator!</p></div><br>
    </div>

<?php require_once __DIR__ . '/_footer.php'; ?>