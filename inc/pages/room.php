<?php 
$room_id = (int)$parameters[1];
if ($room_id <= 0)
	echo "<script>window.location.href = '/rooms';</script>";
else {
?>
<div id="game">
</div>
<?php } ?>