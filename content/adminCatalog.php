<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $cat=$_POST["cat"];
    if($cat==null)
    {
        $cat="";
    }
    $sortType=$_POST["sortType"];
    if($sortType==null)
    {
        $sortType="";
    }
    $keywords=$_POST["keywords"];
    if($keywords==null)
    {
        $keywords="";
    }
print<<<END
<div class="search update row col-md-12">
    <div class="col-md-12">
    <p>Для изменения текста категории выберите её из списка. Для добавления новой, оставьте поле списка категорий пустым.</p>
    </div>
    <div class="col-md">
        <input id="updateCatDatalist" class="catUpdateId" list="catUpdateId" placeholder="Выберите категорию товара" autocomplete="off">
        <datalist id="catUpdateId">
END;
    $cats = DatabaseAPI::GetAllCat();
    if($cats->num_rows>0)
    {
        //перебираем все товары из результата
        while($catToSearch=mysqli_fetch_assoc($cats))
        {
            $text=$catToSearch['title'];
            echo "<option value='$text'>";
        }
    }

print<<<END
        </datalist>
    </div>
    <div class="col-md">
        <input type="text" id="updateCatTextbox" placeholder="Текст категории">
    </div>
    <div class="col-md">
        <input type="button" id='updateCat' value="Добавить/Изменить">
    </div>
    <div class="col-md">
        <input type="button" id='deleteCat' value="Удалить">
    </div>
</div>
<div class="search update row col-md-12">
    <div class="col-md-12">
    <p>Для изменения текста ед. измерения выберите её из списка. Для добавления новой, оставьте поле ед. измерения пустым.</p>
    </div>
    <div class="col-md">
        <input id="updateMeasureDatalist" class="measureUpdateId" list="measureUpdateId" placeholder="Выберите ед. измерения товара" autocomplete="off">
        <datalist id="measureUpdateId">
END;
    $measures = DatabaseAPI::GetAllMerchandiseMeasure();
    if($cats->num_rows>0)
    {
        //перебираем все товары из результата
        while($measure=mysqli_fetch_assoc($measures))
        {
            $text=$measure['text'];
            echo "<option value='$text'>";
        }
    }

print<<<END
        </datalist>
    </div>
    <div class="col-md">
        <input type="text" id="updateMeasureTextbox" placeholder="Текст ед. измерения">
    </div>
    <div class="col-md">
        <input type="button" id='updateMeasure' value="Добавить/Изменить">
    </div>
    <div class="col-md">
        <input type="button" id='deleteMeasure' value="Удалить">
    </div>
</div>
END;
print<<<END
<div class="search col-md-12">
    <input class="catId" list="catId" value="$cat" placeholder="Выберите категорию товара" autocomplete="off">
    <datalist id="catId">
END;
    $cats = DatabaseAPI::GetAllCat();
    if($cats->num_rows>0)
    {
        //перебираем все товары из результата
        while($catToSearch=mysqli_fetch_assoc($cats))
        {
            $text=$catToSearch['title'];
            echo "<option value='$text'>";
        }
    }

print<<<END
    </datalist>
    <input type="button" id='sortButtonAscAdm' value="Показать по возрастанию цены">
</div>
<div class="search col-md-12">
    <input type="text" class="keywords" value="$keywords" placeholder="Слова для поиска" autocomplete="off">
    <input type="button" id='sortButtonDescAdm' value="Показать по убыванию цены">
</div>  
        
<div class="merchAdm col-md-6">
    <form class="merchAdmAddForm">
        <p>Изображение для товара</p>
        <input type="file" name="merchAdmImage" required autocomplete="off">
        <div class="info">
        <input name="merchAdmCat" list="catId" required placeholder="Категория товара" autocomplete="off">
        <datalist id="catId">
END;
            //перебираем все товары из результата
            while($catToList=mysqli_fetch_assoc($cats))
            {
                $text=$catToList['title'];
                echo "<option value='$text'>";
            }
print<<<END
            </datalist>
            <input name="merchAdmTitle" required placeholder="Наименование товара" autocomplete="off">            
            <input name="merchAdmPrice" required placeholder="Цена товара" autocomplete="off">
            <input name="merchAdmMeasure" list="measureId" required placeholder="Единицы измерения товара" autocomplete="off">
            <datalist id="measureId">
END;
            $measures = DatabaseAPI::GetAllMerchandiseMeasure();
            //перебираем все товары из результата
            while($measureToList=mysqli_fetch_assoc($measures))
            {
                $text=$measureToList['text'];
                echo "<option value='$text'>";
            }
print<<<END
            </datalist>
            <textarea name="merchAdmDescription" required placeholder="Описание товара" autocomplete="off"></textarea>
        </div>
        <input type="submit" value="Добавить товар">
    </form>
</div>  
END;
    
    $goods = DatabaseAPI::GetMerchByCat($cat,$keywords,$sortType);
    if($goods->num_rows>0)
    {
        //перебираем все товары из результата
        while($merch=mysqli_fetch_assoc($goods))
        {    
            $id=$merch['id'];
            $title=$merch['title'];
            $description=$merch['description'];
            $price=$merch['price'];
            $image=$merch['image'];
            $cat=$merch['category'];
            $measure=$merch['measure'];         
            
print<<<END
<div merchId="$id" class="merchAdm col-md-6">
    <form class="merchAdmForm">
        <img src="../images/merches/$image" alt="Не удалось загрузить изображение товара $title"  autocomplete="off"'>
        <input type="text" value="$image" name="merchAdmOldImage" hidden>
        <input type="text" value="$id" name="merchAdmId" hidden>
        <p>Изображение для замены текущего</p>
        <input type="file" name="merchAdmImage">
        <div class="info">
        <input name="merchAdmCat" list="catId" value="$cat" required placeholder="Категория товара">
        <datalist id="catId">
END;
            //перебираем все товары из результата
            while($catToList=mysqli_fetch_assoc($cats))
            {
                $text=$catToList['title'];
                echo "<option value='$text'>";
            }
print<<<END
            </datalist>
            <input name="merchAdmTitle" value="$title" required placeholder="Наименование товара">            
            <input name="merchAdmPrice" value="$price" required placeholder="Цена товара">
            <input name="merchAdmMeasure" list="measureId" value="$measure" required placeholder="Единицы измерения товара">
            <datalist id="measureId">
END;
            $measures = DatabaseAPI::GetAllMerchandiseMeasure();
            //перебираем все товары из результата
            while($measureToList=mysqli_fetch_assoc($measures))
            {
                $text=$measureToList['text'];
                echo "<option value='$text'>";
            }
print<<<END
            </datalist>
            <textarea name="merchAdmDescription" required placeholder="Описание товара">$description</textarea>
        </div>
        <input type="submit" value="Изменить информацию">
        <input class="deleteMerch" merchId="$id" imageName="$image" type="button" value="Удалить товар">
    </form>
</div>        
END;
        }
    }
    else
    {
print<<<END
    <div class="col-12">
        <p>Товары отсутствуют</p>
    </div>
END;
    }