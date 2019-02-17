<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $actions = DatabaseAPI::GetActions();
    if($actions->num_rows>0)
    {
        //перебираем все товары из результата
        while($action=mysqli_fetch_assoc($actions))
        {    
            $id=$action['id'];
            $title=$action['title'];
            $text=$action['text'];
            $image=$action['image'];      
            
print<<<END
    <div class="col-12">
        <div class="actions row">
            <div class="col-lg-5 align-self-center">
                <img src="images/actions/$image" alt="Не удалось загрузить изображение $title">
            </div>
            <div class="col-lg-7 align-self-center">
                <p class="actionTitle">$title</p>
                <p>$text</p>
            </div>
        </div>
    </div>
END;
        }
    }
    else
    {
print<<<END
    <div class="col-12">
        <div class="actions row">
            <div class="col-lg-5 align-self-center">
                <img src="images/actions/ThereIsNoActions.png" alt="Нет новостей">
            </div>
            <div class="col-lg-7 align-self-center">
                <p class="actionTitle">Увы, на текущий момент новостей нет.</p>
            </div>
        </div>
    </div>
END;
    }