<?
/************************************************************************
*       Title           :       Art1st Calendar                         *
*       Author          :       Art1st                                  *
*       Version         :       2.0                                     *
*       Status          :       Freeware                                *
*       FileName        :       calendar.php                            *
*       Release date    :       June  12, 2003                          *
*       HomePage        :       http://art1st.far.ru (protected page)   *
*       eMail           :       art1st@freemail.ru                      *
*       Description     :       Simple calendar with some function      *
*       Requirements    :       PHP 3 or higher                         *
*       Thanks To       :       State University of Management (Moscow) *
*************************************************************************
*       Вызов скрипта   :                                               *
*               Просто календарь на текущий месяц - calendar.php        *
*               Календарь на любой месяц -                              *
*                       calendar.php?month=номер месяца-номер года      *
*                       например, "calendar.php?month=6-2003"           *
************************************************************************/
//Индивидуальные настройки скрипта
        $ac_font_size = "12";           //Размер шрифта (только число)
        $ac_font_color = "black";       //Цвет шрифта (в любом представлении: название, RGB, etc. [html-формат])
        $ac_main_color = "white";       //Основной цвет календаря (Обычные дни) (аналогично цвету шрифта)
        $ac_second_color = "silver";    //Второстепенный цвет календаря (аналогично цвету шрифта)
                                        // (Текущий день, заголовок календаря)
        $ac_navigator = TRUE;           //Вывод строки навигации по месяцам (true/false)
//Массив названий месяцев
        $mon_name = array
        (
        "Январь","Февраль","Март","Апрель","Май","Июнь",
        "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"
        );
//Массив продолжительностей месяцев
        $nod = array (31,28,31,30,31,30,31,31,30,31,30,31);
//Пересылка глобальных переменных скрипту
$month = $_REQUEST['sec'];
//Определение месяца и года для календаря
if ($month == '0')
        {
        $ac_month = date("n");
        $ac_year = date("Y");
        $ac_j_dom = date("j");
        $ac_j_dow = date("w");
        }
        else
        {
        list ($ac_month, $ac_year) = explode ("-",$month);
        if ($ac_year<1980) $ac_year = 1980;
        if ($ac_year>2030) $ac_year = 2030;
        if ($ac_month != date("n") or $ac_year != date("Y"))
                {
                $ac_j_dom = 1;
                $ac_j_dow = date("w",mktime(0,0,0,$ac_month,1,$ac_year));
                }
                else
                {
                $ac_j_dom = date("j");
                $ac_j_dow = date("w");
                }
        }
//Корректировка продолжительности февраля в високосном году
if ($ac_year%4==0) {$nod[1]=29;}
//Определение предыдущих/следующих месяцев/годов
$temp_month = $ac_month + 1;
if ($temp_month!=13)
        {
        $ac_month_next = "$temp_month-$ac_year";
        }
        else
        {
        $temp_year = $ac_year + 1;
        $ac_month_next = "1-$temp_year";
        }
$temp_month = $ac_month - 1;
if ($temp_month!=0)
        {
        $ac_month_prev = "$temp_month-$ac_year";
        }
        else
        {
        $temp_year = $ac_year - 1;
        $ac_month_prev = "12-$temp_year";
        }
$temp_year = $ac_year + 1;
$ac_year_next = "$ac_month-$temp_year";
$temp_year = $ac_year - 1;
$ac_year_prev = "$ac_month-$temp_year";
//Определение названия месяца
$ac_mon=$mon_name[$ac_month-1];
//Корректировка номера дня недели из западно-европейской в русскую
if ($ac_j_dow == 0) $ac_j_dow = 7;
//Определение дня недели первого дня месяца
$ac_1_dow = $ac_j_dow - ($ac_j_dom%7 - 1);
if ($ac_1_dow < 1) $ac_1_dow+=7;
if ($ac_1_dow > 7) $ac_1_dow-=7;
//Определение числа дней месяца
$ac_nod = $nod[$ac_month-1];
//Определение количества недель в месяце
$ac_now=5;
if ($ac_1_dow-1+$ac_nod<29) {$ac_now=4;}
        else if ($ac_1_dow-1+$ac_nod>35) {$ac_now=6;}
//Предотвращение вывода текущего дня для нетекущего месяца
if ($ac_month != date("n") or $ac_year != date("Y")) $ac_j_dom = -10;
//Вывод шапки календаря
//$index .= $_REQUEST['dir']." - ".$_REQUEST['sec']." - ".$_REQUEST['four'];
$index .= "
<caption style=\"font-size: $ac_font_size pt; color: $ac_font_color; font-family: verdana\"><b>
$ac_mon $ac_year</b></caption>
<table border='1' cellspacing='0' cellpadding='5' bgcolor='black' style=\"font-size: $ac_font_size pt; color: $ac_font_color; font-family: verdana\">
<tr bgcolor=$ac_second_color>
        <td>пн</td><td>вт</td><td>ср</td><td>чт</td><td>пт</td><td style=\"color: red;\">сб</td><td style=\"color: red;\">вс</td>
";
//Вывод содержимого календаря
for ($i = 0; $i < $ac_now * 7; $i++)
        {
        if ($i%7 == 0) {$index .= "</tr>\n<tr align='center' bgcolor=$ac_main_color >\n\t";}
        if ($i - $ac_1_dow + 2 != $ac_j_dom) {
			$in = $i - $ac_1_dow + 2 .".".$ac_month.".".$ac_year;
			$index .= "<td onclick=\"document.getElementById('data').value='".$in."';\">";
		} 
		else {
			$in = $i - $ac_1_dow + 2 .".".$ac_month.".".$ac_year;
			$index.= "<td bgcolor=$ac_second_color onclick=\"document.getElementById('data').value='".$in."';\" >";
		}
        if (($i < $ac_1_dow - 1)||($i > $ac_nod + $ac_1_dow - 2)) {$index .= "&nbsp;";} else {$index .= $i - $ac_1_dow + 2;}
        $index .= "</td>\n\t";
        }
//Строка навигации по месяцам
if ($ac_navigator) {
	$index .= "</tr><tr bgcolor=$ac_second_color>";
	$index .= "<td colspan='7' align='center'>";
	//$index .= "<img src='Images/Icon/calendar/button-rewind.png' border='0' alt=''
	//	 onclick=\"doSecond('calendar', '".$ac_year_prev."', 0); \" title=\"Год назад\" />&nbsp;";
	
	$index .= "<img src='Images/Icon/calendar/button-reverse_24.png' border='0' alt=''
		 onclick=\"doSecond('calendar', '".$ac_month_prev."', 0); \" title=\"Месяц назад\" />&nbsp;";
	$index .= "<img src='Images/Icon/calendar/button-rec_24.png' border='0' alt=''
		 onclick=\"doSecond('calendar', 0, 0); \" title=\"Текущий месяц\" />&nbsp;";
	$index .= "<img src='Images/Icon/calendar/button-play_24.png' border='0' alt=''
		 onclick=\"doSecond('calendar', '".$ac_month_next."', 0); \" title=\"Месяц вперед\" />&nbsp;";
	//$index .= "<img src='Images/Icon/calendar/button-foward.png' border='0' alt=''
	//	 onclick=\"doSecond('calendar', '".$ac_year_next."', 0); \" title=\"Год вперед\" />&nbsp;";
}
$index .= "</td>";
$index .= "</tr></table>";
//echo $index;

?>
