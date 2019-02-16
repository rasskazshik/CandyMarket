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
    <input type="button" id='sortButtonAsc' value="Показать по возрастанию цены">
</div>
<div class="search col-md-12">
    <input type="text" class="keywords" value="$keywords" placeholder="Слова для поиска">
    <input type="button" id='sortButtonDesc' value="Показать по убыванию цены">
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
<div merchId="$id" class="merch col-md-6">
    <img src="$image" alt='Не удалось загрузить изображение товара "$title"'>
    <div class="info">
        <p class="cat">$cat</p>
        <p class="title">$title</p>
        <p class="description">$description</p>
        <p class="price">Цена: $price руб./$measure</p>
    </div>
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