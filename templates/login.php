<?php $class_form = (count($errors) > 0) ? "form--invalid" : ""; ?>	 
 <form class="form form--add-lot container <?=$class_form?>" action="login.php" method="post"> <!-- form--invalid --> 
    <h2>Вход</h2>
	<?php $classname = (isset($errors['email'])) ? "form__item--invalid" : "";		 
    $value_input = (isset($form['email'])) ? $form['email'] : "";
	$value_error = (isset($errors['email'])) ? $errors['email'] : "введите email"; 
    ?>   
    <div class="form__item  <?=$classname;?>"> <!-- form__item--invalid -->    
       <label for="email">E-mail <sup>*</sup></label>
       <input  id="email" type="text" name="email" placeholder="введите email" value="<?=htmlspecialchars($value_input)?>">
       <span class="form__error"><?=$value_error?></span>	      
	</div>
    <?php $classname = (isset($errors['password'])) ? "form__item--invalid" : "";	
    $value = (isset($errors['password'])) ? $errors['password'] : "введите пароль";
    ?> 
	<div class="form__item form__item--last  <?=$classname;?>">   
       <label for="password">Пароль <sup>*</sup></label>
       <input  id="password" type="password" name="password">     
       <span class="form__error"><?=$value?></span>      
   </div>
   <button type="submit" class="button">Войти</button>
</form>