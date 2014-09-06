<?
function fromRGB($R, $G, $B){
 
 $R=dechex($R);
 If (strlen($R)<2)
 $R='0'.$R;
 
  $G=dechex($G);
 If (strlen($G)<2)
 $G='0'.$G;
 
 $B=dechex($B);
 If (strlen($B)<2)
 $B='0'.$B;
 
 return '#' . $R . $G . $B;
}

function genPastel($mixR = 200, $mixG = 200, $mixB = 200) {
    $red = rand(0,256);
    $green = rand(0,256);
    $blue = rand(0,256);

    // mix the color
    if ($mixR != null) {
        $red = ($red + $mixR) / 2;
        $green = ($green + $mixG) / 2;
        $blue = ($blue + $mixB) / 2;
    }

    $color = fromRGB($red,$green,$blue);
	
    return $color;
}

function genRandomBatch($limit = 5, $maxwidth = 3, $minwidth = 1) {
	$max = $limit;
	$i = 0;

	while($max > 1) {
		$cells[$i] = rand($minwidth, min($max,$maxwidth));
		$max -= $cells[$i];
		$i++;
	}
	shuffle($cells);
	return $cells;
}
?>