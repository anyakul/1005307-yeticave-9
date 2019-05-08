 

    <?php $class_form = (count($errors) > 0) ? "form--invalid" : ""; ?>	 
    <form class="form form--add-lot container <?=$class_form?>"  action="add_lot.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <?php $classname =isset($errors['lot-name']) ? "form__item--invalid" : "";		 
        $value_input = (isset($lot['lot-name']))? $lot['lot-name'] : "Введите наименование лота ";
        $value_error = (isset($errors['lot-name']))? $errors['lot-name'] : "Введите наименование лота";          
		?>     		
        <div class="form__item <?=$classname ?>" > <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="<?=$value_input?>">
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php $classname =isset($errors['category']) ? "form__item--invalid" : "";		 
        $value_input = (isset($lot['category']))? $lot['category'] : "Выберите категорию ";
        $value_error = (isset($errors['category']))? $errors['category'] : "Выберите категорию";          
		?>     		
        <div class="form__item  <?=$classname ?> " >
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option><?=$value_input?></option>
			<?php foreach ($categories as $val) : ?>
                  <option><?=$val['name']?></option>
            <?php endforeach; ?> 			
          </select>		   
          <span class="form__error"><?=$value_error ?></span>
        </div>
      </div>
	  <?php $classname =isset($errors['message']) ? "form__item--invalid" : "";		 
        $value_input = (isset($lot['message']))? $lot['message'] : "Введите описание лота ";
        $value_error = (isset($errors['message']))? $errors['message'] : "Введите описание лота";          
		?>     		
      <div class="form__item form__item--wide <?=$classname ?>  ">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="<?=$value_input?>"></textarea>
        <span class="form__error"><?=$value_error ?></span>
      </div>
	  <?php $classname =isset($errors['image']) ? "form__item--invalid" : "";		 
       $value_input = (isset($lot['image']))? $lot['image'] : "";
       $value_error = (isset($errors['image']))? $errors['image'] . "загрузите снова" : "добавить";          
	   ?>     		
      <div class="form__item form__item--file  <?=$classname ?> ">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file <?=$classname ?> ">
          <input class="visually-hidden " type="file" id="lot-img" name="lot-img"  value="<?=$value_error ?>">  
          <label for="lot-img">
           <?=$value_span?>
          </label>
        </div>
      </div>
	  <?php $classname = (isset($errors['lot-rate'])) ? "form__item--invalid" : "";		 
        $value_input = (isset($lot['lot-rate']))? $lot['lot-rate'] : "0";
        $value_error = (isset($errors['lot-rate']))? $errors['lot-rate'] : "Введите начальную цену";          
		?>     		
      <div class="form__container-three <?=$classname ?>">
        <div class="form__item form__item--small">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="<?=$value_input?>">
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php $classname = (isset($errors['lot-step'])) ? "form__item--invalid" : "";			 
        $value_input = (isset($lot['lot-step']))? $lot['lot-step'] : "0 ";
        $value_error = (isset($errors['lot-step'])) ? $errors['lot-step'] : "Введите шаг ставки";          
		?>     		
        <div class="form__item form__item--small <?=$classname ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="<?=$value_input?>">
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php $classname = (isset($errors['lot-date'])) ? "form__item--invalid" : "";		 
        $value_input = (isset($lot['lot-date']))? $lot['lot-date'] : "Введите описание лота ";
        $value_error = (isset($errors['lot-date']))? $errors['lot-date'] : "Введите дату завершения торгов";          
		?>     		
        <div class="form__item <?=$classname ?> ">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date"  <?=$value_input?> id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
          <span class="form__error"><?=$value_error ?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
 

 