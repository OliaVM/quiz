<?php require_once '/var/www/html/myproject/common/header.php'; ?> 	
<div class="row-fluid">
	<div class="span10" id="box2">
<!-- Authorization -->
<?php require '/var/www/html/src/core/form/avtorization_form.php'; ?>
<!-- Exception during the authorization attempt -->
<?php	if (isset($exAvtoriz3)): ?> 
		<h2 class="redcolor"><?php echo $exAvtoriz3; ?></h2>
<?php	endif; ?> 
<?php	if (isset($exAvtoriz4)): ?>  
		<h2 class="redcolor"><?php echo $exAvtoriz4; ?></h2>
<?php	endif; ?> 
<?php	if (isset($exAvtoriz8)): ?>  
		<h2 class="redcolor"><?php echo $exAvtoriz8; ?></h2>
<?php	endif; ?> 
<?php if (isset($_SESSION['login']) && isset($_SESSION['password'])): ?>
<a href="/index.php">Начать викторину</a><br>
<?php endif; ?>
<a href="/index.php">Перейти на главную страницу</a> 
<?php require_once '/var/www/html/myproject/common/footer.php'; ?> 
	</div>
</div>




