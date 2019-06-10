 <nav class="nav">
      <ul class="nav__list  container">
        <?php foreach ($categories as $category): ?> 
            <li class="nav__item">
			<?php $goto_category_id = "all-lots.php?id=" . $category['id']?>
            <a href=<?=$goto_category_id?>><?=$category['name']?></a>
            </li>
		<?php endforeach; ?>		       
      </ul>
    </nav> 
    <?php $class_form = (count($errors) > 0) ? "form--invalid" : ""; ?>	 
    <form class="form form--add-lot container <?=$class_form?>" action="sign-up.php" method="post"> <!-- form--invalid --><!-- form--invalid -->
      <h2>Регистрация нового аккаунта</h2>
        <?php $classname = (isset($errors['email'])) ? "form__item--invalid" : "";
        $value_input = (isset($user['email']))? $user['email'] : "";
        $value_error = (isset($errors['email']))? $errors['email'] : "Введите email"; ?>
      <div class="form__item <?=$classname ?>"> <!-- form__item--invalid -->
		<label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars($value_input)?>">
        <span class="form__error"><?=$value_error ?></span>
      </div>
        <?php $classname = (isset($errors['password'])) ? "form__item--invalid" : " ";
        $value_input = (isset($user['password']))? $user['password'] : "";
        $value_error = (isset($errors['password']))? $errors['password'] : "Введите пароль"; ?>
      <div class="form__item <?=$classname?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=htmlspecialchars($value_input)?>" >
        <span class="form__error"><?=$value_error ?></span>
      </div>
	  <?php $classname = (isset($errors['name'])) ? "form__item--invalid" : "";
        $value_input = (isset($user['name']))? $user['name'] : "";
        $value_error = (isset($errors['name']))? $errors['name'] : "Введите имя"; ?>
      <div class="form__item <?=$classname?>">
        <label for="name ">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=htmlspecialchars($value_input)?>">
        <span class="form__error"><?=$value_error ?></span>
      </div>
	  	<?php $classname = (isset($errors['message'])) ? "form__item--invalid" : "";
        $value_input = (isset($user['message']))? $user['message'] : "";
        $value_error = (isset($errors['message']))? $errors['message'] : "Напишите как с вами связаться"; ?>
      <div class="form__item <?=$classname?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=htmlspecialchars($value_input)?></textarea>
        <span class="form__error"><?=$value_error ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>