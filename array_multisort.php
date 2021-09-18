<?php
if(!function_exists('php_to_eval')){
	function php_to_eval($array, $base) {
		$js = '';
		if(is_array($array)){
			foreach ($array as $key=>$val) {
				if (is_array($val)) {
					$js .= php_to_eval($val, $base.(is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']"));
				} else {
					$js .= $base;
					$js .= is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']";
					$js .= ' = ';
					//$js .= is_numeric($val) ? ''.$val.'' : "'".addslashes($val)."'";
					$js .= "'".htmlspecialchars($val,ENT_QUOTES)."'";
					$js .= ";<br>";
				}
			}
		}
		return $js;
	}
}
function my_asort($arr){
	$step = count($arr);
	//echo $step;
	while($step > 0){
		$min_value = reset($arr); // First element's value
		$min_key = key($arr); // First element's key
		foreach($arr as $key => $value){
			if($value<$min_value){
				$min_key = $key;
				$min_value = $value;
			}
		}
		$arr_sort[$min_key] = $min_value;
		unset($arr[$min_key],$min_key,$min_value);
		$step--;
	}
	return $arr_sort;
}
if($_REQUEST['show']){
$arr = array(34,55,78,40);
echo "<pre>ASORT ".print_r(my_asort($arr),1)."</pre>";
}
function my_arsort($arr){
	$step = count($arr);
	//echo $step;
	while($step > 0){
		$min_value = reset($arr); // First element's value
		$min_key = key($arr); // First element's key
		foreach($arr as $key => $value){
			if($value>$min_value){
				$min_key = $key;
				$min_value = $value;
			}
		}
		$arr_sort[$min_key] = $min_value;
		unset($arr[$min_key],$min_key,$min_value);
		$step--;
	}
	return $arr_sort;
}
function array_values_recursive($ary)
{
   $lst = array();
   foreach( array_keys($ary) as $k ){
      $v = $ary[$k];
      if (is_scalar($v)) {
         $lst[] = $v;
      } elseif (is_array($v)) {
         $lst = array_merge( $lst,
            array_values_recursive($v)
         );
      }
   }
   return $lst;
}
function my_multisort_sql2($arro,$arr_crit){
	//$arr = array_values($arro);
	//$arr_dist = array_flip(array_values(array_unique(array_values_recursive($arro))));
	
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_dist,1)."</pre>";
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	$arr_coru = array_values(array_diff(array_keys($arr[0]),$arr_cor));
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_cor,1)."</pre>";
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_coru,1)."</pre>";
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<count($arr_cor);$j++){
			$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			if(is_null($arr_back[$j])){
				$arr_back[$j] = $arr_cor[$j];
			}
		}
	}
	$k = count($arr_mov[0]);
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<count($arr_coru);$j++){
			$arr_mov[$i][$j+$k] = $arr[$i][$arr_coru[$j]];
			if(is_null($arr_back[$j+$k])){
				$arr_back[$j+$k] = $arr_coru[$j];
			}
		}
	}
	$arr = $arr_mov;
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr,1)."</pre>";
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	//echo "Crit <pre>".print_r($arr_crit,1)."</pre>";
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_values(array_unique($arr_dist));
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					//$arr_csd = my_asort($arr_csd);
					my_asort($arr_csd);
				}
				else{
					//$arr_csd = my_arsort($arr_csd);
					my_arsort($arr_csd);
				}
				//echo "<pre>".print_r($arr_csd,1)."</pre>";
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
				//echo "<pre>fhfhfh".print_r($arr,1)."</pre>";
			}
			else{
				for($j=0;$j<count($arr);$j++){
					$g = '';
					for($k=0;$k<$i;$k++){
						$g .= "_".array_search($arr[$j][$arr_crit[$k][0]],$arr_dist);
					}	
					$arr_csd[$g][$j] = $arr[$j][$arr_crit[$i][0]];
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							my_asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							my_arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						//$arr_kv = substr($value,strlen($value)-$pad);
						$arr_new[] = $arr[$key];
					}	
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd,$arr_sts);
				//echo "<pre>errerer".print_r($arr,1)."</pre>";
			}
		}
	}
	
	asort($arr_back);
	for($i=0;$i<count($arr);$i++){
		foreach($arr_back as $key => $value){
			$arr_new[$i][$value] = $arr[$i][$key];
		}
	}
	$arr = $arr_new;
	for($j=0;$j<count($arr);$j++){
		$arr[$j] = array_combine($arr_k , $arr[$j]);
	}
	return $arr;
}
function my_multisort_sql3($arro,$arr_crit){
	//$arr = array_values($arro);
	//$arr_dist = array_flip(array_values(array_unique(array_values_recursive($arro))));
	
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_dist,1)."</pre>";
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	$arr_coru = array_values(array_diff(array_keys($arr[0]),$arr_cor));
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_cor,1)."</pre>";
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_coru,1)."</pre>";
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<count($arr_cor);$j++){
			$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			if(is_null($arr_back[$j])){
				$arr_back[$j] = $arr_cor[$j];
			}
		}
	}
	$k = count($arr_mov[0]);
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<count($arr_coru);$j++){
			$arr_mov[$i][$j+$k] = $arr[$i][$arr_coru[$j]];
			if(is_null($arr_back[$j+$k])){
				$arr_back[$j+$k] = $arr_coru[$j];
			}
		}
	}
	$arr = $arr_mov;
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr,1)."</pre>";
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	//echo "Crit <pre>".print_r($arr_crit,1)."</pre>";
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_values(array_unique($arr_dist));
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					//$arr_csd = my_asort($arr_csd);
					asort($arr_csd);
				}
				else{
					//$arr_csd = my_arsort($arr_csd);
					arsort($arr_csd);
				}
				//echo "<pre>".print_r($arr_csd,1)."</pre>";
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
				//echo "<pre>fhfhfh".print_r($arr,1)."</pre>";
			}
			else{
				for($j=0;$j<count($arr);$j++){
					$g = '';
					for($k=0;$k<$i;$k++){
						$g .= "_".array_search($arr[$j][$arr_crit[$k][0]],$arr_dist);
					}	
					$arr_csd[$g][$j] = $arr[$j][$arr_crit[$i][0]];
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						//$arr_kv = substr($value,strlen($value)-$pad);
						$arr_new[] = $arr[$key];
					}	
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd,$arr_sts);
				//echo "<pre>errerer".print_r($arr,1)."</pre>";
			}
		}
	}
	
	asort($arr_back);
	for($i=0;$i<count($arr);$i++){
		foreach($arr_back as $key => $value){
			$arr_new[$i][$value] = $arr[$i][$key];
		}
	}
	$arr = $arr_new;
	for($j=0;$j<count($arr);$j++){
		$arr[$j] = array_combine($arr_k , $arr[$j]);
	}
	return $arr;
}
function my_multisort_sql4($arro,$arr_crit){
	//$arr = array_values($arro);
	//$arr_dist = array_flip(array_values(array_unique(array_values_recursive($arro))));
	
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_dist,1)."</pre>";
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	$arr_coru = array_values(array_diff(array_keys($arr[0]),$arr_cor));
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_cor,1)."</pre>";
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_coru,1)."</pre>";
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<(count($arr_cor)+1);$j++){
			if($j<count($arr_cor)){
				$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			}
			else{
				$arr_mov[$i][$j] = $i;
			}
		}
	}
	$arr = $arr_mov;
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr,1)."</pre>";
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	//echo "Crit <pre>".print_r($arr_crit,1)."</pre>";
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_values(array_unique($arr_dist));
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					//$arr_csd = my_asort($arr_csd);
					asort($arr_csd);
				}
				else{
					//$arr_csd = my_arsort($arr_csd);
					arsort($arr_csd);
				}
				//echo "<pre>".print_r($arr_csd,1)."</pre>";
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
				//echo "<pre>fhfhfh".print_r($arr,1)."</pre>";
			}
			else{
				for($j=0;$j<count($arr);$j++){
					$g = '';
					for($k=0;$k<$i;$k++){
						$g .= "_".array_search($arr[$j][$arr_crit[$k][0]],$arr_dist);
					}	
					$arr_csd[$g][$j] = $arr[$j][$arr_crit[$i][0]];
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						//$arr_kv = substr($value,strlen($value)-$pad);
						$arr_new[] = $arr[$key];
					}	
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd,$arr_sts);
				//echo "<pre>errerer".print_r($arr,1)."</pre>";
			}
		}
	}
	for($i=0;$i<count($arr);$i++){
		$arr_ret[] = $arro[$arr[$i][count($arr_crit)]];
	}
	return $arr_ret;
}
function my_multisort_sql5($arro,$arr_crit){
	//$arr = array_values($arro);
	//$arr_dist = array_flip(array_values(array_unique(array_values_recursive($arro))));
	
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_dist,1)."</pre>";
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	$arr_coru = array_values(array_diff(array_keys($arr[0]),$arr_cor));
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_cor,1)."</pre>";
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr_coru,1)."</pre>";
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<(count($arr_cor)+1);$j++){
			if($j<count($arr_cor)){
				$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			}
			else{
				$arr_mov[$i][$j] = $i;
			}
		}
	}
	$arr = $arr_mov;
	//echo "<pre>HHHHHHHHHHHHHHHHHHHHHHHH ".print_r($arr,1)."</pre>";
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	//echo "Crit <pre>".print_r($arr_crit,1)."</pre>";
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_map('strval', $arr_dist);
	$arr_dist = array_values(array_unique($arr_dist));
	$arr_dist = array_flip($arr_dist);
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					//$arr_csd = my_asort($arr_csd);
					asort($arr_csd);
				}
				else{
					//$arr_csd = my_arsort($arr_csd);
					arsort($arr_csd);
				}
				//echo "<pre>".print_r($arr_csd,1)."</pre>";
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
				//echo "<pre>fhfhfh".print_r($arr,1)."</pre>";
			}
			else{
				for($j=0;$j<count($arr);$j++){
					$g = '';
					for($k=0;$k<$i;$k++){
						$g .= "_".$arr_dist[strval($arr[$j][$arr_crit[$k][0]])];
					}	
					$arr_csd[$g][$j] = $arr[$j][$arr_crit[$i][0]];
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						//$arr_kv = substr($value,strlen($value)-$pad);
						$arr_new[] = $arr[$key];
					}	
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd,$arr_sts);
				//echo "<pre>errerer".print_r($arr,1)."</pre>";
			}
		}
	}
	$c = count($arr_crit);
	for($i=0;$i<count($arr);$i++){
		$arr_ret[] = $arro[$arr[$i][$c]];
	}
	return $arr_ret;
}
function my_multisort_sql6($arro,$arr_crit){	
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<(count($arr_cor)+1);$j++){
			if($j<count($arr_cor)){
				$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			}
			else{
				$arr_mov[$i][$j] = $i;
			}
		}
	}
	$arr = $arr_mov;
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_map('strval', $arr_dist);
	$arr_dist = array_values(array_unique($arr_dist));
	$arr_dist = array_flip($arr_dist);
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					asort($arr_csd);
				}
				else{
					arsort($arr_csd);
				}
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
			}
			else{
				if(is_null($arr_indexes)){
					for($j=0;$j<count($arr);$j++){
						$arr_indexes[$j] = "_".$arr_dist[strval($arr[$j][$arr_crit[0][0]])];
						$arr_csd[$arr_indexes[$j]][$j] = $arr[$j][$arr_crit[$i][0]];
					}
				}
				else{
					for($j=0;$j<count($arr);$j++){
						$arr_indexes[$j] .= "_".$arr_dist[strval($arr[$j][$arr_crit[$i-1][0]])];
						$arr_csd[$arr_indexes[$j]][$j] = $arr[$j][$arr_crit[$i][0]];
					}
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						$arr_new[] = $arr[$key];
						$arr_indexes_new[] = $arr_indexes[$key];
					}	
				}
				$arr = $arr_new;
				$arr_indexes = $arr_indexes_new;
				unset($arr_new,$arr_csd,$arr_sts,$arr_indexes_new);
			}
		}
	}
	$c = count($arr_crit);
	for($i=0;$i<count($arr);$i++){
		$arr_ret[] = $arro[$arr[$i][$c]];
	}
	return $arr_ret;
}
function my_multisort_sql7($arro,$arr_crit){
	for($j=0;$j<count($arro);$j++){
		$arr[$j] = array_values($arro[$j]);
	}
	$arr_k = array_keys($arro[0]);
	$pad = strlen(count($arr));
	for($i=0;$i<count($arr_crit);$i++){
		$arr_cor[$i] = $arr_crit[$i][0];
		if(is_null($arr_crit_new[$i])){
			$arr_crit_new[$i] = array($i,$arr_crit[$i][1]);
		}	
	}
	for($i=0;$i<count($arr);$i++){
		for($j=0;$j<(count($arr_cor)+1);$j++){
			if($j<count($arr_cor)){
				$arr_mov[$i][$j] = $arr[$i][$arr_cor[$j]];
			}
			else{
				$arr_mov[$i][$j] = $i;
			}
		}
	}
	$arr = $arr_mov;
	unset($arr_mov);
	$arr_crit = $arr_crit_new;
	unset($arr_crit_new);
	$arr_dist = array();
    for($i=0;$i<count($arr_crit);$i++){
		for($j=0;$j<count($arr);$j++){
			array_push($arr_dist,$arr[$j][$arr_crit[$i][0]]);
		}
	}
	$arr_dist = array_values(array_unique($arr_dist));
	for($i=0;$i<count($arr_crit);$i++){
		if($arr_crit[$i][1]){
			if($i==0){
				for($j=0;$j<count($arr);$j++){
					$arr_csd[$j] = $arr[$j][$arr_crit[$i][0]];
				}
				if($arr_crit[$i][1] == 1){
					//$arr_csd = my_asort($arr_csd);
					asort($arr_csd);
				}
				else{
					//$arr_csd = my_arsort($arr_csd);
					arsort($arr_csd);
				}
				//echo "<pre>".print_r($arr_csd,1)."</pre>";
				foreach($arr_csd as $key => $value){
					$arr_new[] = $arr[$key];
				}
				$arr = $arr_new;
				unset($arr_new,$arr_csd);
				//echo "<pre>fhfhfh".print_r($arr,1)."</pre>";
			}
			else{
				if(is_null($arr_indexes)){
					for($j=0;$j<count($arr);$j++){
						//$arr_indexes[$j] = "_".$arr_dist[strval($arr[$j][$arr_crit[0][0]])];
						$arr_indexes[$j] = "_".array_search($arr[$j][$arr_crit[0][0]],$arr_dist);
						$arr_csd[$arr_indexes[$j]][$j] = $arr[$j][$arr_crit[$i][0]];
					}
				}
				else{
					for($j=0;$j<count($arr);$j++){
						//$arr_indexes[$j] .= "_".$arr_dist[strval($arr[$j][$arr_crit[$i-1][0]])];
						$arr_indexes[$j] .= "_".array_search($arr[$j][$arr_crit[$i-1][0]],$arr_dist);
						$arr_csd[$arr_indexes[$j]][$j] = $arr[$j][$arr_crit[$i][0]];
					}
				}
				//sorting each array of prices
				foreach($arr_csd as $key => $value){
					if(count($arr_csd[$key]) > 1){
						if($arr_crit[$i][1] == 1){
							//$arr_csd[$key] = my_asort($arr_csd[$key]);
							asort($arr_csd[$key]);
						}
						else{
							//$arr_csd[$key] = my_arsort($arr_csd[$key]);
							arsort($arr_csd[$key]);
						}
					}
				}
				foreach($arr_csd as $k1 => $v1){
					foreach($v1 as $key => $value){
						//$arr_kv = substr($value,strlen($value)-$pad);
						$arr_new[] = $arr[$key];
						$arr_indexes_new[] = $arr_indexes[$key];
					}	
				}
				$arr = $arr_new;
				$arr_indexes = $arr_indexes_new;
				unset($arr_new,$arr_csd,$arr_sts,$arr_indexes_new);
				//echo "<pre>errerer".print_r($arr,1)."</pre>";
			}
		}
	}
	$c = count($arr_crit);
	for($i=0;$i<count($arr);$i++){
		$arr_ret[] = $arro[$arr[$i][$c]];
	}
	return $arr_ret;
}
if(!function_exists('php_to_eval')){
	function php_to_eval($array, $base) {
		$js = '';
		if(is_array($array)){
			foreach ($array as $key=>$val) {
				if (is_array($val)) {
					$js .= php_to_eval($val, $base.(is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']"));
				} else {
					$js .= $base;
					$js .= is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']";
					$js .= ' = ';
					//$js .= is_numeric($val) ? ''.$val.'' : "'".addslashes($val)."'";
					$js .= "'".addslashes($val)."'";
					$js .= ";\n";
				}
			}
		}
		return $js;
	}
}
function php_to_js($array, $base) {
   $js = '';
   foreach ($array as $key=>$val) {
       if (is_array($val)) {
           $js .= php_to_js($val, $base.(is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']"));
       } else {
           $js .= $base;
           $js .= is_numeric($key) ? '['.$key.']' : "['".addslashes($key)."']";
           $js .= ' = ';
           $js .= is_numeric($val) ? ''.$val.'' : "'".addslashes($val)."'";
           $js .= ";\n";
       }
   }
   return $base." = new Array();\n".$js;
}
if(!function_exists('array_orderby')){
    function array_orderby(){
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
                }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}

//The Array
/*$arr[0] = array(2,14,null);
$arr[1] = array(2,14,'b');
$arr[2] = array(1,4,'d');
$arr[3] = array(1,14,'c');*/

/*
$arr[0] = array(2,14,0);
$arr[1] = array(2,14,3);
$arr[2] = array(1,4,5);
$arr[3] = array(1,14,'b');
*/

$arr[0] = array('status' => 1, 'pret' => 456.7, 'cod_oferta' => 'a','test' => array(0));
$arr[1] = array('status' => 0, 'pret' => 23.7, 'cod_oferta' => 'a','test' => 1);
$arr[2] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[3] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 3);
$arr[4] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[5] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[6] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[7] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'b','test' => 3);
$arr[8] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[9] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[10] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 4);
$arr[11] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[12] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[13] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 5);
$arr[14] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'n','test' => 1);
$arr[15] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[16] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 7);
$arr[17] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[18] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[19] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[20] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[21] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[22] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[23] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'c','test' => 1);
$arr[24] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[25] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[26] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'c','test' => 1);
$arr[27] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[28] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'c','test' => 1);
$arr[29] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[30] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 6);
$arr[31] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[32] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[33] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'b','test' => 1);
$arr[34] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[35] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[36] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[37] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[38] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[39] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[40] = array('status' => 0, 'pret' => 24.7, 'cod_oferta' => 'a','test' => 1);
$arr[41] = array('status' => 1, 'pret' => 27.7, 'cod_oferta' => 'a'.chr(13).'b','test' => 6);
$arr[42] = array('status' => -1, 'pret' => 27.7, 'cod_oferta' => 'a','test' => 1);
$arr[43] = array('status' => -1, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[44] = array('status' => 5, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[45] = array('status' => 16, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 6);
$arr[46] = array('status' => -2, 'pret' => 29.7, 'cod_oferta' => 'a','test' => 1);
$arr[47] = array('status' => 5, 'pret' => 29.75, 'cod_oferta' => 'a','test' => 6);
$arr[48] = array('status' => 500, 'pret' => 29.7, 'cod_oferta' => 'a'.chr(13).'b','test' => 6);
/*
$arr[] = array('status' => 3,'pret' => 1);
$arr[] = array('status' => 2,'pret' => 0);
$arr[] = array('status' => 2,'pret' => -1);
$arr[] = array('status' => 3,'pret' => -2);
$arr[] = array('status' => 1,'pret' => 2);
$arr[] = array('status' => 1,'pret' => -1);
*/
//The Criteria Array
//$arr_crit - 0 ->column index , 1 -> order 1- ascending , 2 -descending
$arr_crit[0][0] = 0;
$arr_crit[0][1] = 1;
$arr_crit[1][0] = 1;
$arr_crit[1][1] = 1;
$arr_crit[2][0] = 2;
$arr_crit[2][1] = 1;
/*$arr_crit[2][0] = 2;
$arr_crit[2][1] = 1;
$arr_crit[3][0] = 3;
$arr_crit[3][1] = 2;*/
/*$arr_crit[2][0] = 2;
$arr_crit[2][1] = 1;*/
/*$arr_crit[2][0] = 2;
$arr_crit[2][1] = 1;*/

function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
echo "<pre>Criteria ".print_r($arr_crit,1)."</pre>";
echo "<pre>Original ".print_r($arr,1)."</pre>";
$start = microtime_float();
$arr_hot = my_multisort_sql6($arr,$arr_crit);
for($i=0;$i<count($arr_hot);$i++){
	$arr_hot1[] = implode("_",$arr_hot[$i]);
}
echo "<pre>Sorted my_multisort_sql6 : ".print_r($arr_hot,1)."</pre>";
$stop = microtime_float();
//echo php_to_js($arr,"arr");
echo "<br>TIMP TOTAL pentru 6&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$secunde :".($stop-$start);
$start = microtime_float();
echo "<br>".php_to_eval($arr_hot1,'\$arr');
$arr_hot = my_multisort_sql7($arr,$arr_crit);
$arr_hot1 = [];
for($i=0;$i<count($arr_hot);$i++){
	$arr_hot1[] = implode("_",$arr_hot[$i]);
}
echo "<pre>Sorted my_multisort_sql7 : ".print_r($arr_hot,1)."</pre>";
$stop = microtime_float();
//echo php_to_js($arr,"arr");
echo "<br>TIMP TOTAL pentru 7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\$secunde :".($stop-$start);
echo "<br>".php_to_eval($arr_hot1,'\$arr');

?>