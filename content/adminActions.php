<div class="col-12">
        <form class="admAction">
            <div class="row">
                <div class="col-lg-5 align-self-center admActionImgCol">                    
                    <p>Изображение для акции</p>
                    <input type="file" name="actionImage" required>
                </div>
                <div class="col-lg-7 align-self-center">
                    <input type="text" name="actionAdmTitle" placeholder="Название акции" required placeholder="Наименование акции">
                    <textarea name="actionAdmText" required placeholder="Текст акции" required placeholder="Текст акции"></textarea>
                </div>
            </div>
        <input class="maxWidth" type="submit" value="Добавить">
        </form>
    </div>
<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';

    $actions = DatabaseAPI::GetActions();
    //перебираем все товары из результата
    while($action=mysqli_fetch_assoc($actions))
    {    
        $id=$action['id'];
        $title=$action['title'];
        $text=$action['text'];
        $image=$action['image'];      
            
print<<<END
    <div class="col-12">
        <form class="admAction">
            <div class="row">
                <div class="col-lg-5 align-self-center admActionImgCol">
                    <img src="../$image" alt="Нет акций">
                    <p>Изображение для замены текущего</p>
                    <input type="file" name="actionImage">
                </div>
                <div class="col-lg-7 align-self-center">
                    <input type="text" name="actionAdmTitle" placeholder="Название акции" value=$title" required>
                    <textarea name="actionAdmText" required placeholder="Текст акции" required>$text</textarea>
                </div>
            </div>
        <input class="maxWidth" type="submit" value="Изменить">
        <input class="maxWidth" type="button" value="Удалить">
        </form>
    </div>
END;
    }