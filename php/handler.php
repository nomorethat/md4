<?php
	require_once "auxiliary_functions.php"; 
	require_once "steps.php"; 
	
	if($_POST["open_message"] !== false){
		$open_message = $_POST["open_message"];
		md4($open_message);
	}
	
	function md4($open_message){
		$binnary_open_message = "";
		for($i = 0; $i < strlen($open_message); $i++){
			$binnary_open_message .= bstr2bin($open_message[$i]); 
		}
		
		$extended_binnary_open_message = adding_missing_bits($binnary_open_message); 
		
		$binnary_record_of_open_message_length = decbin(strlen($open_message)); 
		$extended_binnary_record_of_open_message_length = extended_binnary_record_of_open_message_length($binnary_record_of_open_message_length); 
		$extended_binnary_open_message = $extended_binnary_open_message.$extended_binnary_record_of_open_message_length; 

		$array_of_32_bits_words = break_into_32_bits_words($extended_binnary_open_message); 

		initialization_of_MD_buffer();
			
		main_cycle($array_of_32_bits_words);
	}
?>