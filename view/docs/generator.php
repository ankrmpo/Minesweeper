<?php

$dim = 6;
$GameField = array();
$EndField = array();

for($i = 0;$i < $dim; ++$i)
{
    $red = array_fill(0, $dim, 0);
    array_push($GameField, $red);
    array_push($EndField, $red);
}

$koliko = 0.20 * $dim * $dim;
$koliko = intval($koliko);
$positions = array();

while(count($positions) < $koliko)
{
    $i = count($positions);
    $broj1 = mt_rand(0, $dim - 1);
    $broj2 = mt_rand(0, $dim - 1);
    
    $ima = false;
    for($j = 0; $j < $i; ++$j)
    {
        if($positions[$j][0] === $broj1 && $positions[$j][1] === $broj2)
        {
            $ima = true;
            break;
        }
    }

    if($ima === false)
    {
        $positions[$i][0] = $broj1;
        $positions[$i][1] = $broj2;
    }
}

for($i = 0; $i < count($positions); ++$i)
    $EndField[$positions[$i][0]][$positions[$i][1]] = -1;

for($i = 0;$i < $dim; ++$i)
{
    for($j = 0; $j < $dim; ++$j)
    {
        if($EndField[$i][$j] === -1)
        {
            // ako je lijevi rub
            if($i === 0)
            {
                // prvo kutovi
                if($j === 0)
                {
                    if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                    if($EndField[$i + 1][$j + 1] >= 0) ++$EndField[$i + 1][$j + 1];
                    if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                }
                else if($j === $dim - 1)
                {
                    if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                    if($EndField[$i + 1][$j - 1] >= 0) ++$EndField[$i + 1][$j - 1];
                    if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                }
                // sada nisu kutovi pa ima spod i iznad polja
                else
                {
                    if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                    if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                    if($EndField[$i + 1][$j - 1] >= 0) ++$EndField[$i + 1][$j - 1];
                    if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                    if($EndField[$i + 1][$j + 1] >= 0) ++$EndField[$i + 1][$j + 1];
                }
            }
            // ako je desni rub
            else if($i === $dim - 1)
            {
                // prvo kutovi
                if($j === 0)
                {
                    if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                    if($EndField[$i - 1][$j + 1] >= 0) ++$EndField[$i - 1][$j + 1];
                    if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                }
                else if($j === $dim - 1)
                {
                    if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                    if($EndField[$i - 1][$j - 1] >= 0) ++$EndField[$i - 1][$j - 1];
                    if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                }
                // sada nisu kutovi pa ima ispod i iznad polja
                else
                {
                    if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                    if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                    if($EndField[$i - 1][$j - 1] >= 0) ++$EndField[$i - 1][$j - 1];
                    if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                    if($EndField[$i - 1][$j + 1] >= 0) ++$EndField[$i - 1][$j + 1];
                }
            }
            // ako je gornji rub
            else if($j === 0)
            {
                // sada ne treba provjeravati kutove jer su obrađeni u ranijim slučajevima
                // postoje lijevo i desno elementi
                if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                if($EndField[$i - 1][$j + 1] >= 0) ++$EndField[$i - 1][$j + 1];
                if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                if($EndField[$i + 1][$j + 1] >= 0) ++$EndField[$i + 1][$j + 1];
            }
            // ako je donji rub
            else if($j === $dim - 1)
            {
                if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                if($EndField[$i - 1][$j - 1] >= 0) ++$EndField[$i - 1][$j - 1];
                if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                if($EndField[$i + 1][$j - 1] >= 0) ++$EndField[$i + 1][$j - 1];
            }
            // ako nije nigdje na rubu
            else
            {
                if($EndField[$i - 1][$j - 1] >= 0) ++$EndField[$i - 1][$j - 1];
                if($EndField[$i - 1][$j] >= 0) ++$EndField[$i - 1][$j];
                if($EndField[$i - 1][$j + 1] >= 0) ++$EndField[$i - 1][$j + 1];
                if($EndField[$i][$j - 1] >= 0) ++$EndField[$i][$j - 1];
                if($EndField[$i][$j + 1] >= 0) ++$EndField[$i][$j + 1];
                if($EndField[$i + 1][$j - 1] >= 0) ++$EndField[$i + 1][$j - 1];
                if($EndField[$i + 1][$j] >= 0) ++$EndField[$i + 1][$j];
                if($EndField[$i + 1][$j + 1] >= 0) ++$EndField[$i + 1][$j + 1];
            }
        }
    }
}

        
for($i = 0; $i < $dim; ++$i)
    for($j = 0; $j < $dim; ++$j)
        if($EndField[$i][$j] === 0) $EndField[$i][$j] = 9;

?>