//флаг активного анимирования навигации
var navNotAnimate=true;

//выгрузка контента с предназначением ресурса
function NavToCatalog(){     
    navNotAnimate=false;
    $(".navigation a").removeClass("active");
    $(".navToCatalog").addClass("active");
    $.post("content/catalog.php",function(responce){
        $(".contentRow").slideToggle(500,function(){
            $(".contentRow").html(responce);
            $(".contentRow").slideToggle(500,function(){                    
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

//выгрузка контента со списком торговых точек
function NavToActions(){ 
    navNotAnimate=false;
    $(".navigation a").removeClass("active");
    $(".navToActions").addClass("active");
    $.post("content/actions.php",function(responce){
        $(".contentRow").slideToggle(500,function(){
            $(".contentRow").html(responce);
            $(".contentRow").slideToggle(500,function(){
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

//выгрузка контента со списком предложений торговой сети Пятерочка
function NavToContacts(){     
        navNotAnimate=false;
        $(".navigation a").removeClass("active");
        $(".navToContacts").addClass("active");
        $.post("content/contacts.php",function(responce){
            $(".contentRow").slideToggle(500,function(){
                $(".contentRow").html(responce);
                $(".contentRow").slideToggle(500,function(){
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

//выгрузка контента со списком предложений торговой сети Пятерочка
function NavToProcessOrder(){     
        navNotAnimate=false;
        $(".navigation a").removeClass("active");
        $(".navToProcessOrder").addClass("active");
        $.post("content/processOrder.php",function(responce){
            $(".contentRow").slideToggle(500,function(){
                $(".contentRow").html(responce);
                $(".contentRow").slideToggle(500,function(){
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