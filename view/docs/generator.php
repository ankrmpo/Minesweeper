<?php

$dim=30;
$GameField=array();
$EndField=array();

for($i=0;$i<$dim;++$i)
{
    $red=array_fill(0,$dim,0);
    array_push($GameField,$red);
    array_push($EndField,$red);
}

$koliko=0.17*$dim*$dim;
$trenutno=0;
$positions=array();
for($i=0;$i<$dim;++$i)
{
    $broj=mt_rand(0,$koliko-$trenutno);
    while($broj>$dim/3) $broj=mt_rand(0,$koliko-$trenutno);
    if($broj>0) $positions=array_rand($EndField[$i],$broj);
    if(is_array($positions)) foreach($positions as $pos) $EndField[$i][$pos]=-1;
    $trenutno+=$broj;
}

for($i=0;$i<$dim;++$i)
{
    for($j=0;$j<$dim;++$j)
        echo $EndField[$i][$j];
    echo '<br>';    
}

?>