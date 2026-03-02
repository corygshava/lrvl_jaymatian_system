<?php
	// require_once __DIR__.'/piece_dd.php';
?>

@include('pieces.dd')

<div id="datahole"></div>

<script>
	datahole.innerHTML = dd(window['cur_record']);
</script>
