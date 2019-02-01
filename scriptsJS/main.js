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
        $.post("content/processOrder.php",function(responce){
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

$(document).ready(Navigate());

//добавление товара в корзину
function addItemToOffer(id,num)
{
    //получаем строку с товарами
    var offer = $.cookie("offer");
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
        var itemsList = {}; 
        //флаг наличия добавляемого товара в списке
        var exist = false;
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
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

//добавление товара в корзину
function vemoveItemToOffer(id,num)
{
    //получаем строку с товарами
    var offer = $.cookie("offer");
    //если она пуста - ничего не делаем
    if (offer!="")
    {
        //массив объектов элементов заказа
        var itemsList = {}; 
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
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
    //если она не пуста
    if (offer!="")
    {
        //массив объектов элементов заказа
        var itemsList = {}; 
        //формируем массив строк элементов заказа
        var items = offer.split("/");
        //перебираем все строки
        items.forEach(function(item, i, arr) {
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
        var itemsList = {};
        return itemsList;
    }
}