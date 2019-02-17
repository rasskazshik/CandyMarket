<div class="col-12">
        <form class="admAction admActionAdd">
            <div class="row">
                <div class="col-lg-5 align-self-center admActionImgCol">                    
                    <p>Изображение для новости</p>
                    <input type="file" name="actionImage" required>
                </div>
                <div class="col-lg-7 align-self-center">
                    <input type="text" name="actionAdmTitle" placeholder="Название новости" required placeholder="Наименование новости">
                    <textarea name="actionAdmText" required placeholder="Текст новости" required placeholder="Текст новости"></textarea>
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
        <form class="admAction admActionUpdate">
            <div class="row">
                <div class="col-lg-5 align-self-center admActionImgCol">
                    <input type="text" value="$image" name="actionOldImage" hidden>
                    <input type="text" value="$id" name="actionId" hidden>
                    <img src="../images/actions/$image" alt="Нет новостей">
                    <p>Изображение для замены текущего</p>
                    <input type="file" name="actionImage">
                </div>
                <div class="col-lg-7 align-self-center">
                    <input type="text" name="actionAdmTitle" placeholder="Название новости" value="$title" required>
                    <textarea name="actionAdmText" required placeholder="Текст новости" required>$text</textarea>
                </div>
            </div>
        <input class="maxWidth" type="submit" value="Изменить">
        <input class="maxWidth deleteAction" actionId="$id" image="$image" type="button" value="Удалить">
        </form>
    </div>
END;
    }