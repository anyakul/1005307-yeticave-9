    <form class="form form--add-lot container <?=$class_form?>" action="login.php" method="post"> <!-- form--invalid --><!-- form--invalid -->
      <h2>Вход</h2>

      <div class="form__item "> <!-- form__item--invalid -->
        <?php $classname = isset($errors['email']) ? "form__item--invalid" : "";		 
        $value = isset($form['email']) ? $form['email'] : "";?> 
		<label for="email">E-mail <sup>*</sup></label>
        <input class="<?=$classname;?>" id="email" type="text" name="email" placeholder="<?=$value;?>">
		<?php if ($classname): ?>
        <span class="form__error"><?=$errors['email']?></span>
	    <?php endif; ?>     
	  </div>


	  <div class="form__item form__item--last">
  <?php $classname = isset($errors['password']) ? "form__item--invalid" : "";	
        $value = isset($form['password']) ? $form['password'] : "";?>     
  	 	<label for="password">Пароль <sup>*</sup></label>
        <input class="<?=$classname;?>" id="password" type="password" name="password">
		<?php if ($classname): ?>
        <span class="form__error"><?=$errors['password'];?></span>
	    <?php endif; ?>      
	  </div>
      <button type="submit" class="button">Войти</button>
    </form>