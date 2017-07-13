<?php require_once '/var/www/html/myproject/common/header.php'; ?> 
<div class="row-fluid">
	<div class="span10" id="box2">
		<!-- Registration -->	
		<?php require '/var/www/html/src/core/form/registration_form.php'; ?>
		<!--  Exception during the registration attempt -->
		<?php	if (isset($exRegistration5)): ?> 
			<h2 class="redcolor"><?php echo $exRegistration5; ?></h2>
		<?php	endif; ?> 
		<?php	if (isset($exRegistration6)): ?>  
			<h2 class="redcolor"><?php echo $exRegistration6; ?></h2>
		<?php	endif; ?>
		<!-- Link "Main page" -->
		<a href="/index.php">Перейти на главную страницу</a>  
		<?php require_once '/var/www/html/myproject/common/footer.php'; ?> 
	</div>
</div>




