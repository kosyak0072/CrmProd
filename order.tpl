<link rel="stylesheet" href="http://diplomkin.com.ua/userRegForm.css">
<link rel="stylesheet" href="http://dipsverka.com/resources/css/blitzer/jqueryui.css">
<link type="text/css" rel="stylesheet" type="text/css" href="http://dipsverka.com/resources/css/jCleverTemplate/alice/css/jClever.css">
<link type="text/css" href="http://dipsverka.com/resources/css/jCleverTemplate/alice/css/jquery.jscrollpane.css" rel="stylesheet" media="all">
<script src="http://dipsverka.com/resources/js/jqueryui.js"></script>
<script src="http://dipsverka.com/resources/js/jClever/build/jClever.min.js" type="text/javascript"></script>
<script src="http://dipsverka.com/resources/js/jClever/src/jquery.jscrollpane.min.js" type="text/javascript"></script>
<script src="http://dipsverka.com/resources/js/jClever/src/jquery.mousewheel.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
    
        jQuery('.jClever').jClever({
            applyTo: {
                checkbox:true,
                radio:true,
                file:true,
                select:true
            }
        });
    });
</script>


<form  method="post" class="jClever" action = "http://dipsverka.com/User/Index/order/">
    <div class="userRegForm">
        <h1 class="header">Заказ работы</h1>
        <div class="wrap">
            <div class="reg-user">
                <h2 class="header">Обязательные поля</h2>
                <label>Тема работы<sup>*</sup></label><br>
                <input name="orderName" type="text" placeholder="Введите тему вашей работы"><br>
                <label>Предмет<sup>*</sup></label><br>
                <input name="predmet" type="text" placeholder="Введите название предмета, по которому будет выполнена работа"><br>
                <label>Тип работы<sup>*</sup></label><br>
                <select name="predmetType">
                    <option value="0">Выберите тип работы</option>
                    <option value="Автореферат">Автореферат</option>
                    <option value="Дипломная работа">Дипломная работа</option>
                    <option value="Дипломная работа MBA<">Дипломная работа MBA</option>
                    <option value="Диссертация">Диссертация</option>
                    <option value="Доклад">Доклад</option>
                    <option value="Контрольная работа">Контрольная работа</option>
                    <option value="Курсовая работа">Курсовая работа</option>
                    <option value="Магистерская работа">Магистерская работа</option>
                    <option value="Ответы на билеты">Ответы на билеты</option>
                    <option value="Отчёт по практике">Отчёт по практике</option>
                    <option value="Перевод">Перевод</option>
                    <option value="Презентация">Презентация</option>
                    <option value="Реферат">Реферат</option>
                    <option value="Статьи к диссертации">Статьи к диссертации</option>
                    <option value="Эссе">Эссе</option>
                </select>
                <label>Объем работы<sup>*</sup></label><br>
                <input name="orderCount" type="Text" placeholder="Количество страниц в работе"><br>
                <label>Количество источников<sup>*</sup></label><br>
                <input name="listSource" type="text" placeholder="Количество источников, которое необходимо использовать в работе"><br>
                <label>Срок выполнения<sup>*</sup></label><br>
                <input name="orderDtEnd" id="datepicker" type="text" placeholder="Дата, в которую вы хотите получить вашу работу" readonly><br>
                <script>
                    $(function() {
                        $( "#datepicker" ).datepicker();
                    });
                </script>
                <label>Прикрепляемый файл</label><br>
                <div class="fileselect">
                    <input name="file" type="file">
                </div><br>
            </div>
            <div class="reg-user">
                <h2 class="header">Контактная информация</h2>
                <label>Фамилия, имя, отчество<sup>*</sup></label><br>
                <input name="customerName" type="text" placeholder="Введите фамилию, имя и отчество"><br>
                <label>Email<sup>*</sup></label><br>
                <input name="customerEmail" type="text" placeholder="Введите ваш электронный почтовый ящик"><br>
                <label>Номер мобильного телефона<sup>*</sup></label><br>
                <input name="cutomerPhone" type="text" placeholder="Введите номер вашего мобильного телефона"><br>
                <label>Дополнительные требования </label><br>
                <textarea name="addInf" placeholder="Ваши комментарии и пожелания" rows="14" class="cleararea"></textarea>
                <?php if( isset($_SESSION['site']) ):?>
                    <input type="hidden" id="site" name="site" value="<?php echo $_SESSION['site']?>">
                <?php else : ?>
                    <input type="hidden" id="site" name="site" value="http://www.diplomkin.com.ua/">
                <?php endif?>
                <input name="order" type="submit" value="Оформить">
            </div>
        </div>
    </div>
    <script>
    $("jClever-element-file-name").ready(function(){
        $("jClever-element-file-name").css('width','82% !important')
    });
    </script>
</form>