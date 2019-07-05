<?php require_once __DIR__ . '/_header.php'; ?>
    <link rel="stylesheet" type="text/css" href="view/style/ranking.css">
</head>
<body>
    <header>
        <h1>Ranking</h1>
    </header>
    <body>
    <table class="tablica">
    <tr>
        <th>Username</th>
        <th>Score</th>
    </tr>
    <?php
        foreach($ranks as $rank)
        {
            echo '<tr>';
            echo '<td>'. $rank->username .'</td>';
            echo '<td>'. $rank->points .'</td>';
            echo '</tr>';
        }
    ?>
    </table>
    <a class="back" href="choose.php">Back to menu</a>
    </body>

<?php require_once __DIR__ . '/_footer.php'; ?>