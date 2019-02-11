//флаг активного анимирования навигации
var navNotAnimate=true;

//выгрузка контента с предназначением ресурса
function NavToCatalog(){     
    navNotAnimate=false;
    $(".navigation a").removeClass("active");
    $(".navToCatalog").addClass("active");
    $.post("content/catalog.php",function(responce){
        $(".contentRow").fadeToggle(700,function(){
            $(".contentRow").html(responce);
            $(".contentRow").fadeToggle(700,function(){                    
                navNotAnimate=true;
            });
        }); 
   });
}

$(".navToCatalog").on("click",function(event){
    event.preventDefault();
    if(navNotAnimate&&!$(".navToCatalog").hasClass("active"))
    {   
        NavToCatalog();
        $.cookie("navTo","ToCatalog");
    }
});

//выгрузка контента с акциями
function NavToActions(){ 
    navNotAnimate=false;
    $(".navigation a").removeClass("active");
    $(".navToActions").addClass("active");
    $.post("content/actions.php",function(responce){
        $(".contentRow").fadeToggle(700,function(){
            $(".contentRow").html(responce);
            $(".contentRow").fadeToggle(700,function(){
                navNotAnimate=true;
            });
        }); 
   });
}

$(".navToActions").on("click",function(event){
    event.preventDefault();
    if(navNotAnimate&&!$(".navToActions").hasClass("active"))
    {    
        NavToActions();
        $.cookie("navTo","ToActions");
    }
});

//выгрузка контента с контактной информацией
function NavToContacts(){     
        navNotAnimate=false;
        $(".navigation a").removeClass("active");
        $(".navToContacts").addClass("active");
        $.post("content/contacts.php",function(responce){
            $(".contentRow").fadeToggle(700,function(){
                $(".contentRow").html(responce);
                $(".contentRow").fadeToggle(700,function(){
                    navNotAnimate=true;
                });
            }); 
       });
}
$(".navToContacts").on("click",function(event){
    event.preventDefault();
    if(navNotAnimate&&!$(".navToContacts").hasClass("active"))
    {  
        NavToContacts();
        $.cookie("navTo","ToContacts");
    }
});

//выгрузка контента с оформлением заказа
function NavToProcessOrder(){     
        navNotAnimate=false;
        $(".navigation a").removeClass("active");
        $(".navToProcessOrder").addClass("active");
        var goods = getOfferItems();
        $.post("content/processOrder.php",{goods:goods},function(responce){
            $(".contentRow").fadeToggle(700,function(){
                $(".contentRow").html(responce);
                $(".contentRow").fadeToggle(700,function(){
                    navNotAnimate=true;
                });
            }); 
       });
}
$(".shopingCart").on("click",function(event){
    event.preventDefault();
    if(navNotAnimate&&!$(".navToProcessOrder").hasClass("active"))
    {  
        NavToProcessOrder();
        $.cookie("navTo","ToProcessOrder");
    }
});

//навигация с запросом cookie-данных
function Navigate(){
    var navigate = $.cookie("navTo");
    if (navigate==""||navigate==null) {
        $.cookie("navTo","ToCatalog");
        NavToCatalog();
        return;
    }
    if (navigate=="ToCatalog") {
        NavToCatalog();
        return;
    }
    if (navigate=="ToActions") {
        NavToActions();
        return;
    }
    if (navigate=="ToContacts") {
        NavToContacts();
        return;
    }
    if (navigate=="ToProcessOrder") {
        NavToProcessOrder();
        return;
    }
}

$(document).ready(function(){
    updateTotalPrice();
    Navigate();
});

//добавление товара в корзину
function addItemToOffer(id,num)
{
    //получаем строку с товарами
    var offer = $.cookie("offer");
    if(offer==null)
    {
        offer="";
    }
    //если она пуста
    if (offer=="")
    {
        //добавляем товар
        var items = id+"#"+num+"/";
        $.cookie("offer",items);
    }
    //если в ней что-то есть
    else
    {
        //массив объектов элементов заказа
        var itemsList = []; 
        //флаг наличия добавляемого товара в списке
        var exist = false;
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
            if(item==="")
            {
                return;
            }
            //разбиваем строку и получаем пару id и num
            var buff = item.split("#");
            //формируем объект
            var offerItem = new Object();
            //формируем поле id объекта
            offerItem.id=buff[0];
            //если добавляемый товар имеется в списке (текущий элемент)
            if(buff[0]==id)
            {
                //флаг что товар в списке
                exist=true;
                //формируем поле num объекта
                //увеличиваем количество товара в списке на указанную величину
                offerItem.num=Number(buff[1])+Number(num);
            }
            //если товар не в списке (текущий элемент)
            else
            {
                //формируем поле num объекта
                offerItem.num=buff[1];
            }
            //добавляем объект в массив объектов элементов заказа
            itemsList.push(offerItem);
        });
        //если товар существовал в списке
        if(exist)
        {
            //уже увеличили его количество на 1
            //поэтому формируем строку из массива
            //пустая строка
            var items = "";
            itemsList.forEach(function(item, i, arr) {
                //добавляем элемент к строке
                items+= item.id+"#"+item.num+"/";
            });
            //сохраняем список в куки
            $.cookie("offer",items);
        }
        //если товара в списке не было
        else
        {
            //будет первым
            var items = offer+id+"#"+num+"/";
            $.cookie("offer",items);
        }
    }
}

//удаление товара из корзины
function removeItemToOffer(id,num)
{
    //получаем строку с товарами
    var offer = $.cookie("offer");
    if(offer==null)
    {
        offer="";
    }
    //если она пуста - ничего не делаем
    if (offer!="")
    {
        //массив объектов элементов заказа
        var itemsList = []; 
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
            if(item==="")
            {
                return;
            }
            //разбиваем строку и получаем пару id и num
            var buff = item.split("#");
            //формируем объект
            var offerItem = new Object();

            //если удаляемый товар имеется в списке (текущий элемент)
            if(buff[0]==id)
            {
                //формируем поле num объекта
                //уменьшаем количество товара в списке на указанную величину
                offerItem.num=Number(buff[1])-Number(num);
                //если оставшееся количество больше нуля
                if(offerItem.num>0)
                {
                    //формируем поле id объекта
                    offerItem.id=buff[0];
                    //добавляем объект в массив объектов элементов заказа
                    itemsList.push(offerItem);
                }
            }
            //если товар не в списке (текущий элемент)
            else
            {
                //формируем поле id объекта
                offerItem.id=buff[0];
                //формируем поле num объекта
                offerItem.num=buff[1];
                //добавляем объект в массив объектов элементов заказа
                itemsList.push(offerItem);
            }            
        });
        //формируем строку из массива
        //пустая строка
        var items = "";
        itemsList.forEach(function(item, i, arr) {
            //добавляем элемент к строке
            items+= item.id+"#"+item.num+"/";
        });
        //сохраняем список в куки
        $.cookie("offer",items);
    }
}

//получение списка товаров (массив объектов с полями id и num)
function getOfferItems(){
    //получаем строку с товарами
    var offer = $.cookie("offer");
    if(offer==null)
    {
        offer="";
    }
    //если она не пуста
    if (offer!="")
    {
        //массив объектов элементов заказа
        var itemsList = []; 
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
            if(item==="")
            {
                return;
            }
            //разбиваем строку и получаем пару id и num
            var buff = item.split("#");
            //формируем объект
            var offerItem = new Object();
            offerItem.id=buff[0];
            offerItem.num=Number(buff[1]);
            itemsList.push(offerItem);           
        });
        //возвращаем массив объектов заказа
        return itemsList;
        
    }
    //если пуста
    else
    {
        //массив объектов элементов заказа
        var itemsList = [];
        return itemsList;
    }
}

//очистка корзины
function ClearOfferItems(){
    $.cookie("offer","");
}

//анимированная функция показа и сокрытия модального окна 
//с учетом отобржения флексов
function FadeToggleFlex(target,time,callback)
{
    if($(target).css("display")=='none')
    {
        $(target).css({'display':'flex'});
        $(target).animate({opacity:1},time,function()
        {
            if (typeof callback === "function")
            {
                callback();
            }
        });
    }
    else
    {
        $(target).animate({opacity:0},time,function(){
            $(target).css({'display':'none'});
            if (typeof callback === "function")
            {
                callback();
            }            
        });
    }
}

//показ модального окна выбора количества товара по клику по товару
$(document).on('click','.merch',function(){
   var id = $(this).attr("merchId");
   var content = '<form merchId='+id+' class="setCount"> <input type="text" class="countMerch" placeholder="Количество товара" required pattern="^\\d+$" title="Количество товара - целое число"> <input type="submit" class="Accept" value="Принять"> <input type="button" class="Cancel" value="Отмена"> </form>';
   $(".addMerchLayout .col").empty();
   $(".addMerchLayout .col").append(content);
   FadeToggleFlex($(".addMerchLayout"),500);
});

//закрытие модального окна по клику по подложке и кнопке закрытия
$(document).on('click',".addMerchLayout, .Cancel",function(){
    FadeToggleFlex($(".addMerchLayout"),500,function(){
        $(".addMerchLayout .col").html("");
    });
});

//останавливаем всплытие до подложки (иначе сработает закрытие модального окошка)
$(document).on('click',".addMerchLayout .col",function(event){
    event.stopPropagation();
});

//указываем количество товара и добавляем к корзине
$(document).on("submit",".setCount",function(event){
    event.preventDefault();
    var id = $(this).attr("merchId");
    var count = $(".countMerch").val();
    addItemToOffer(id,count);
    //обновляем общий ценник
    updateTotalPrice();
    //скрываем модальное окно
    FadeToggleFlex($(".addMerchLayout"),500,function(){
        $(".addMerchLayout .col").html("");
    });
});

//получаем общую цену товара
function updateTotalPrice(){
    var goods = getOfferItems();
    $.post("scriptsPHP/setTotalPrice.php",{goods:goods},function(content){
        $(".offerPrice").html(content);
    });
};

//уменьшение количества товара непосредственно в корзине
$(document).on("click","[subId]",function(){
   var id = $(this).attr("subId");
   var content = '<form merchId='+id+' class="setRemoveCount"> <input type="text" class="countMerch" placeholder="Количество товара" required pattern="^\\d+$" title="Количество товара - целое число"> <input type="submit" class="Accept" value="Принять"> <input type="button" class="Cancel" value="Отмена"> </form>';
   $(".addMerchLayout .col").empty();
   $(".addMerchLayout .col").append(content);
   FadeToggleFlex($(".addMerchLayout"),500);
});

//указываем количество товара для уменьшения и уменьшаем
$(document).on("submit",".setRemoveCount",function(event){
    event.preventDefault();
    var id = $(this).attr("merchId");
    var count = $(".countMerch").val();
    removeItemToOffer(id,count);
    //скрываем модальное окно
    FadeToggleFlex($(".addMerchLayout"),500,function(){
        var goods = getOfferItems();
        navNotAnimate=false;
        $.post("content/processOrder.php",{goods:goods},function(responce){
            $(".contentRow").fadeToggle(700,function(){
                $(".contentRow").html(responce);
                //обновляем общий ценник
                updateTotalPrice();
                $(".contentRow").fadeToggle(700,function(){
                    navNotAnimate=true;
                });
            }); 
        });
        $(".addMerchLayout .col").html("");
    });
});

//увеличение количества товара непосредственно в корзине
$(document).on("click","[addId]",function(){
   var id = $(this).attr("addId");
   var content = '<form merchId='+id+' class="setAddCount"> <input type="text" class="countMerch" placeholder="Количество товара" required pattern="^\\d+$" title="Количество товара - целое число"> <input type="submit" class="Accept" value="Принять"> <input type="button" class="Cancel" value="Отмена"> </form>';
   $(".addMerchLayout .col").empty();
   $(".addMerchLayout .col").append(content);
   FadeToggleFlex($(".addMerchLayout"),500);
});

//указываем количество товара для увеличения и увеличиваем
$(document).on("submit",".setAddCount",function(event){
    event.preventDefault();
    var id = $(this).attr("merchId");
    var count = $(".countMerch").val();
    addItemToOffer(id,count);
    //скрываем модальное окно
    FadeToggleFlex($(".addMerchLayout"),500,function(){
        var goods = getOfferItems();
        navNotAnimate=false;
        $.post("content/processOrder.php",{goods:goods},function(responce){
            $(".contentRow").fadeToggle(700,function(){
                $(".contentRow").html(responce);
                //обновляем общий ценник
                updateTotalPrice();
                $(".contentRow").fadeToggle(700,function(){
                    navNotAnimate=true;
                });
            }); 
        });
        $(".addMerchLayout .col").html("");
    });
});

//отправляем данные заказа в БД
$(document).on("submit",".sendOfferData",function(event){
    event.preventDefault();
    var goods = getOfferItems();
    var address = $(".address").val();
    var phone = $(".phone").val();
    var name = $(".name").val();
    $.post("scriptsPHP/addOfferDataToDB.php",{goods:goods,address:address,phone:phone, name:name},function(responce){
        if(responce=="true")
        {
            ClearOfferItems();
            navNotAnimate=false;
            $.post("content/processOrder.php",{report:"Заказ успешно зарегистрирован. В процессе его обработки с Вами свяжется наш менеджер."},function(responce){
                $(".contentRow").fadeToggle(700,function(){
                    $(".contentRow").html(responce);
                    //обновляем общий ценник
                    updateTotalPrice();
                    $(".contentRow").fadeToggle(700,function(){
                        navNotAnimate=true;
                    });
                }); 
            });
        }
        else
        {
            var goods = getOfferItems();
            navNotAnimate=false;
            $.post("content/processOrder.php",{report:"Во время регистрации заказа произошла техническая ошибка. Попробуйте повторить попытку позднее."},function(responce){
                $(".contentRow").fadeToggle(700,function(){
                    $(".contentRow").html(responce);
                    //обновляем общий ценник
                    updateTotalPrice();
                    $(".contentRow").fadeToggle(700,function(){
                        navNotAnimate=true;
                    });
                }); 
            });
        }
    });
});