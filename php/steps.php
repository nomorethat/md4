<?php

	function adding_missing_bits($binnary_open_message){
		$extended_binnary_open_message = $binnary_open_message;
		$extended_binnary_open_message .= "1";
		if((strlen($extended_binnary_open_message))%512 == 448)
			return $extended_binnary_open_message;
		else{
			while(1){
				$extended_binnary_open_message .= "0";
				if((strlen($extended_binnary_open_message))%512 == 448)
					break;
			}
		}
		return $extended_binnary_open_message;
	}
	
	function extended_binnary_record_of_open_message_length($binnary_record_of_open_message_length){
		if(strlen($binnary_record_of_open_message_length) < 64){
			while(1){
				if(strlen($binnary_record_of_open_message_length) == 64)
					break;
				$binnary_record_of_open_message_length = "0".$binnary_record_of_open_message_length;	
			}
		}
		$binnary_record_of_open_message_length = substr($binnary_record_of_open_message_length, 32).substr($binnary_record_of_open_message_length, 0, 32);
		return $binnary_record_of_open_message_length;
	}
	
	function break_into_32_bits_words($extended_binnary_open_message){ // разбиваем на слова по 32 бита
		$j = 0;
		$step = 0;
		while(1){
			if($step === strlen($extended_binnary_open_message))
				break;
			for($i = $step; $i < ($step + 32); $i++){
				$machine_word .= $extended_binnary_open_message[$i];
			}
			$array_of_32_bits_words[$j] = $machine_word;
			$machine_word = "";
			$j++;
			$step += 32;
		}
		$array_of_32_bits_words = reorganization_32_bits_words($array_of_32_bits_words); // см. описание функции
		return $array_of_32_bits_words;
	}

	function reorganization_32_bits_words($array_of_32_bits_words){  
		for($i = 0; $i < count($array_of_32_bits_words); $i++){
			$word_of_32_bits = $array_of_32_bits_words[$i];
			for($j = 0; $j < strlen($array_of_32_bits_words[$i]); $j++){
				$first_word_of_8_bits = substr($word_of_32_bits, 0, 8);
				$second_word_of_8_bits = substr($word_of_32_bits, 8, 8);
				$third_word_of_8_bits = substr($word_of_32_bits, 16, 8);
				$four_word_of_8_bits = substr($word_of_32_bits, 24, 8);
			}
			$array_of_32_bits_words[$i] = $four_word_of_8_bits.$third_word_of_8_bits.$second_word_of_8_bits.$first_word_of_8_bits;
		}
		return $array_of_32_bits_words;
	}
	
	function initialization_of_MD_buffer(){
		$a = extend_binnary_record_for_32_bits(base_convert("01234567", 16, 2));
		$b = extend_binnary_record_for_32_bits(base_convert("89abcdef", 16, 2));
		$c = extend_binnary_record_for_32_bits(base_convert("fedcba98", 16, 2));
		$d = extend_binnary_record_for_32_bits(base_convert("76543210", 16, 2));
		return $MD_buffer = Array("a" => $a, "b" => $b, "c" => $c, "d" => $d);
	}
	
	function F($x, $y, $z){ 	
		$not_x = bitwise_not($x);
		$f = (($x & $y) | ($not_x & $z));
		return $f;
	}
	
	function G($x, $y, $z){ 	
		$f = (($x & $y) | ($x & $z) | ($y & $z));
		return $f;
	}
	
	function H($x, $y, $z){ 
		$f = $x ^ $y ^ $z;
		return $f;
	}
	
	function main_cycle($M){ 
		$MD_buffer = initialization_of_MD_buffer(); 
		
		$a = $MD_buffer["a"];
		$b = $MD_buffer["b"];
		$c = $MD_buffer["c"];
		$d = $MD_buffer["d"];

		$block_512 = 0; 
		for($i = $block_512; $i < ($block_512 + 16); $i++){ 
			if($block_512 === count($M))
				break;
			for($j = 0; $j < 16; $j++){ 
				$X[$j] = $M[$block_512 + $j + 1];
			}
			
			$aa = $a;
			$bb = $b;
			$cc = $c;
			$dd = $d;
			
			
			$a = round_1($a, $b, $c, $d, $X[0], 3);
			$d = round_1($d, $a, $b, $c, $X[1], 7);
			$c = round_1($c, $d, $a, $b, $X[2], 11);
			$b = round_1($b, $c, $d, $a, $X[3], 19);
			
			$a = round_1($a, $b, $c, $d, $X[4], 3);
			$d = round_1($d, $a, $b, $c, $X[5], 7);
			$c = round_1($c, $d, $a, $b, $X[6], 11);
			$b = round_1($b, $c, $d, $a, $X[7], 19);
			
			$a = round_1($a, $b, $c, $d, $X[8], 3);
			$d = round_1($d, $a, $b, $c, $X[9], 7);
			$c = round_1($c, $d, $a, $b, $X[10], 11);
			$b = round_1($b, $c, $d, $a, $X[11], 19);
			
			$a = round_1($a, $b, $c, $d, $X[12], 3);
			$d = round_1($d, $a, $b, $c, $X[13], 7);
			$c = round_1($c, $d, $a, $b, $X[14], 11);
			$b = round_1($b, $c, $d, $a, $X[15], 19);
			
			
			$a = round_2($a, $b, $c, $d, $X[0], 3);
			$d = round_2($d, $a, $b, $c, $X[4], 5);
			$c = round_2($c, $d, $a, $b, $X[8], 9);
			$b = round_2($b, $c, $d, $a, $X[12], 13);
			
			$a = round_2($a, $b, $c, $d, $X[1], 3);
			$d = round_2($d, $a, $b, $c, $X[5], 5);
			$c = round_2($c, $d, $a, $b, $X[9], 9);
			$b = round_2($b, $c, $d, $a, $X[13], 13);
			
			$a = round_2($a, $b, $c, $d, $X[2], 3);
			$d = round_2($d, $a, $b, $c, $X[6], 5);
			$c = round_2($c, $d, $a, $b, $X[10], 9);
			$b = round_2($b, $c, $d, $a, $X[14], 13);
			
			$a = round_2($a, $b, $c, $d, $X[3], 3);
			$d = round_2($d, $a, $b, $c, $X[7], 5);
			$c = round_2($c, $d, $a, $b, $X[11], 9);
			$b = round_2($b, $c, $d, $a, $X[15], 13);

			
			$a = round_3($a, $b, $c, $d, $X[0], 3);
			$d = round_3($d, $a, $b, $c, $X[8], 9);
			$c = round_3($c, $d, $a, $b, $X[4], 11);
			$b = round_3($b, $c, $d, $a, $X[12], 15);
			
			$a = round_3($a, $b, $c, $d, $X[2], 3);
			$d = round_3($d, $a, $b, $c, $X[10], 9);
			$c = round_3($c, $d, $a, $b, $X[6], 11);
			$b = round_3($b, $c, $d, $a, $X[14], 15);
			
			$a = round_3($a, $b, $c, $d, $X[1], 3);
			$d = round_3($d, $a, $b, $c, $X[9], 9);
			$c = round_3($c, $d, $a, $b, $X[5], 11);
			$b = round_3($b, $c, $d, $a, $X[13], 15);
			
			$a = round_3($a, $b, $c, $d, $X[3], 3);
			$d = round_3($d, $a, $b, $c, $X[11], 9);
			$c = round_3($c, $d, $a, $b, $X[7], 11);
			$b = round_3($b, $c, $d, $a, $X[15], 15);
			
			
			$a = extend_binnary_record_for_32_bits(decbin(bindec($a) + bindec($aa)));
			$b = extend_binnary_record_for_32_bits(decbin(bindec($b) + bindec($bb)));
			$c = extend_binnary_record_for_32_bits(decbin(bindec($c) + bindec($cc)));
			$d = extend_binnary_record_for_32_bits(decbin(bindec($d) + bindec($dd)));

			$block_512 += 16; 
		}
		
		$MD_4_digest = base_convert($a, 2, 16).base_convert($b, 2, 16).base_convert($c, 2, 16).base_convert($d, 2, 16);
		echo var_dump($MD_4_digest);
	}
	
	function round_1($a, $b, $c, $d, $k, $s){ 
		$a = (bindec($a) + bindec(F($b, $c, $d)) + bindec($k));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, 3);
		return $a;
	}
	
	function round_2($a, $b, $c, $d, $k, $s){ 
		$a = (bindec($a) + bindec(G($b, $c, $d)) + bindec($k) + hexdec("5A827999"));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, 3);
		return $a;
	}
	
	function round_3($a, $b, $c, $d, $k, $s){ 
		$a = (bindec($a) + bindec(H($b, $c, $d)) + bindec($k) + hexdec("6ED9EBA1"));
		$a = extend_binnary_record_for_32_bits(decbin($a));
		$a = cyclic_shift_to_the_left($a, 3);
		return $a;
	}
?>