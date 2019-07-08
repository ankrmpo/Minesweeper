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

$koliko=0.20*$dim*$dim;
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
    for($j=0;$j<$dim;++$j)
        if($EndField[$i][$j]==-1)
        {
            if($j!=0) $EndField[$i][$j-1]++;
            if($j!=$dim-1) $EndField[$i][$j+1]++;
            if($i!=0) $EndField[$i-1][$j]++;
            if($i!=$dim-1) $EndField[$i+1][$j]++;
            if($i!=0 && $j!=0) $EndField[$i-1][$j-1]++;
            if($i!=0 && $j!=$dim-1) $EndField[$i-1][$j+1]++;
            if($i!=$dim-1 && $j!=0) $EndField[$i+1][$j-1]++;
            if($i!=$dim-1 && $j!=$dim-1) $EndField[$i+1][$j+1]++;
        }
        
for($i=0;$i<$dim;++$i)
    for($j=0;$j<$dim;++$j)
        if($EndField[$i][$j]==0) $EndField[$i][$j]=9;

?>