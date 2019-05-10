 <?php $class_form = (count($errors) > 0) ? "form--invalid" : ""; ?>	 
    <form class="form form--add-lot container <?=$class_form?>" action="login.php" method="post"> <!-- form--invalid --><!-- form--invalid -->
      <h2>Вход</h2>
        <?php $classname =isset($errors['email']) ? "form__item--invalid" : "";		 
        $value_input = (isset($user['email']))? $user['email'] : "Введите email ";
        $value_error = (isset($errors['email']))? $errors['email'] : "Введите email";
		$value_both_error = (password_verify($_POST['password'], $user['password'])) ? $errors['key'] : "Вы ввели неверный email/пароль";?>
		<span class="form__error"><?=$value_both_error?></span>
      <div class="form__item <?=$classname?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="<?=$value_input?>">
        <span class="form__error"><?=$value_error?></span>
      </div>
	    <?php $classname =isset($errors['password']) ? "form__item--invalid" : "";		 
        $value_input = (isset($user['password']))? $user['password'] : "Введите пароль ";
        $value_error = (isset($errors['password']))? $errors['password'] : "Вы ввели неправильный пароль"; ?>
      <div class="form__item form__item--last <?=$classname?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="<?=$value_input?>">
        <span class="form__error"> <?=$value_error?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>