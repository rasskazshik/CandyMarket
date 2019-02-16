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
<div class="search col-md-12">
    <input class="catId" list="catId" value="$cat" placeholder="Выберите категорию товара">
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
    <input type="text" class="keywords" value="$keywords" placeholder="Слова для поиска">
    <input type="button" id='sortButtonDescAdm' value="Показать по убыванию цены">
</div>  
        
<div class="merchAdm col-md-6">
    <form class="merchAdmAddForm">
        <p>Изображение для товара</p>
        <input type="file" name="merchAdmImage">
        <div class="info">
        <input name="merchAdmCat" list="catId" required placeholder="Категория товара">
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
            <input name="merchAdmTitle" required placeholder="Наименование товара">            
            <input name="merchAdmPrice" required placeholder="Цена товара">
            <input name="merchAdmMeasure" list="measureId" required placeholder="Единицы измерения товара">
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
            <textarea name="merchAdmDescription" required placeholder="Описание товара"></textarea>
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
        <img src="../$image" alt='Не удалось загрузить изображение товара "$title"'>
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
        <input type="button" value="Удалить товар">
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