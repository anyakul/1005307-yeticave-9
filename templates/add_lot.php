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
    <form class="form form--add-lot container <?=$class_form?>"  action="add_lot.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <?php
           $classname =isset($errors['lot-name']) ? "form__item--invalid" : "";
           $value_input = (isset($lot['lot-name']))? $lot['lot-name'] : "";
           $value_error = (isset($errors['lot-name']))? $errors['lot-name'] : "Введите наименование лота";
           
        ?>     		
        <div class="form__item <?=$classname ?>" > <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder= "Введите наименование лота" value="<?=htmlspecialchars($value_input)?>" >
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php
           $classname =isset($errors['category']) ? "form__item--invalid" : "";
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
	  <?php
         $classname = (isset($errors['message'])) ? "form__item--invalid" : "";
         $value_input = (isset($lot['message']))? $lot['message'] : "";
         $value_error = (isset($errors['message']))? $errors['message'] : "Введите описание лота";
      ?>  
  
      <div class="form__item form__item--wide <?=$classname ?>  ">
        <label for="message">Описание <sup>*</sup></label>
       <textarea id="message" name="message" placeholder="Напишите описание лота"><?=htmlspecialchars($value_input)?></textarea>	 
        <span class="form__error"><?=$value_error?></span>
      </div>
	  <?php
         $classname = (isset($errors['lot-img']))? "form__item--invalid" : "";
         $value_input = (isset($lot['lot-img']))? $lot['lot-img'] : "добавить";
         $value_error = (isset($errors['lot-img']))? $errors['lot-img'] : " ";
         
      ?>     		
      <div class="form__item form__item--file ">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file <?=$classname ?>  ">
          <input class="visually-hidden" type="file" id="lot-img" name="lot-img"  value="<?=htmlspecialchars($value_input)?>">  
          <label   for="lot-img">добавить</label>
		  <span class="form__error"><?=$value_error ?></span>
        </div>
      </div>
	  <?php
         $classname = (isset($errors['lot-rate'])) ? "form__item--invalid" : "";
         $value_input = (isset($lot['lot-rate']))? $lot['lot-rate'] : "";
         $value_error = (isset($errors['lot-rate']))? $errors['lot-rate'] : "Введите начальную цену";
        ?>     		
      <div class="form__container-three ">
        <div class="form__item form__item--small <?=$classname ?> ">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value=<?=htmlspecialchars($value_input)?>>
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php $classname = (isset($errors['lot-step'])) ? "form__item--invalid" : "";
        $value_input = (isset($lot['lot-step']))? $lot['lot-step'] : "";
        $value_error = (isset($errors['lot-step'])) ? $errors['lot-step'] : "Введите шаг ставки";
        ?>     		
        <div class="form__item form__item--small <?=$classname ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0" value=<?=htmlspecialchars($value_input)?>>
          <span class="form__error"><?=$value_error ?></span>
        </div>
		<?php
           $classname =   (isset($errors['lot-date'])) ? "form__item--invalid" : "";
           $value_input = (isset($lot['lot-date']))? $lot['lot-date'] : " ";
           $value_error = (isset($errors['lot-date']))? $errors['lot-date'] : "Введите дату завершения торгов в формате ГГГГ-ММ-ДД ";
        ?> 		
        <div class="form__item <?=$classname?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date "   id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value=<?=$value_input?>>
          <span class="form__error"><?=$value_error?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button" >Добавить лот</button>
    </form>
 

 