<?php
require_once ('../setup.php');
require_once ( 'fukc_num_str.php');

if (!isset($target)) {
echo '
<html>
 <head>
  <title>Отправка документов</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
 </head> 
<body>
Выслать документы (договор и счет)?<br>
Отправка займет 30-60 секунд, дождитесь надписи "Готово".<br><br>
<a href="prg_sent.php?IDzak='.$IDzak.'&target=1"><button>Только менеджеру</button></a>
<a href="prg_sent.php?IDzak='.$IDzak.'&target=2"><button>Менеджеру и заказчику</button></a>
</body>
</html>';
}

else {

$data_zak = mysql_query ("SELECT * FROM zakaz WHERE ID = '$IDzak'") or die (mysql_error());
$rowz = mysql_fetch_array($data_zak);
$IDzak=$rowz['ID'];
$IDorg=$rowz['IDorg'];
$dost=$rowz['dost'];
$knigi=$rowz['knigi'];
$vklady=$rowz['vklady'];
$uchet=$rowz['uchet'];
$korob=$rowz['korob'];
$rashod=$rowz['rashod'];
$sumdost=$rowz['sumdost'];
$extra=$rowz['extra'];
if ($extra == 0) {$extra=1;}

if ($uchet>0) {
    $data_uch = mysql_query ("SELECT * FROM prices WHERE position = 'uchet'") or die (mysql_error());
    $rowu = mysql_fetch_array($data_uch);
    $priceuch=$rowu['price'];
    $summauch=$uchet*$priceuch;
    }
if ($korob>0) {
    $data_kor = mysql_query ("SELECT * FROM prices WHERE position = 'korob'") or die (mysql_error());
    $rowk = mysql_fetch_array($data_kor);
    $pricekor=$rowk['price'];
    $summakor=$korob*$pricekor;
    }
if ($rashod>0) {
    $data_ras = mysql_query ("SELECT * FROM prices WHERE position = 'rashod'") or die (mysql_error());
    $rowr = mysql_fetch_array($data_ras);
    $priceras=$rowr['price'];
    $summaras=$rashod*$priceras;
    }

if ($knigi<1) {unset($cenaknigi);unset($summaknigi);}
if ($knigi==1) {$cenaknigi = 185; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>1 AND $knigi<5) {$cenaknigi = 180; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>4 AND $knigi<10) {$cenaknigi = 175; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>9 AND $knigi<25) {$cenaknigi = 165; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>24 AND $knigi<101) {$cenaknigi = 150; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>100 AND $knigi<250) {$cenaknigi = 120; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>249) {$cenaknigi = 110; $summaknigi = $cenaknigi*$knigi;}

if ($vklady<1) {unset($cenavklady);unset($summavklady);}
if ($vklady==1) {$cenavklady = 175; $summavklady = $cenavklady*$vklady;}
if ($vklady>1 AND $vklady<5) {$cenavklady = 170; $summavklady = $cenavklady*$vklady;}
if ($vklady>4 AND $vklady<10) {$cenavklady = 165; $summavklady = $cenavklady*$vklady;}
if ($vklady>9 AND $vklady<25) {$cenavklady = 150; $summavklady = $cenavklady*$vklady;}
if ($vklady>24 AND $vklady<101) {$cenavklady = 110; $summavklady = $cenavklady*$vklady;}
if ($vklady>100 AND $vklady<250) {$cenavklady = 85; $summavklady = $cenavklady*$vklady;}
if ($vklady>249) {$cenavklady = 80; $summavklady = $cenavklady*$vklady;}

$date = date("d.m.Y");
$number = str_pad($IDzak, 5, "0", STR_PAD_LEFT);
$all = $knigi + $vklady + $korob + $rashod + $uchet;
$summa = $summaknigi + $summavklady + $summakor + $summauch + $summaras;
$nds = round(($summa-($summa/1.18)), 2);
$textsumm = num2str($summa);

$data_org = mysql_query ("SELECT * FROM orglist WHERE ID = '$IDorg'") or die (mysql_error());
$rowo = mysql_fetch_array($data_org);
$IDorg=$rowo['ID'];
$orgname=$rowo['orgname'];
$orgname = mb_convert_encoding($orgname,"utf-8","Windows-1251");
$person=$rowo['person'];
$person = mb_convert_encoding($person,"utf-8","Windows-1251");
$osn=$rowo['osn'];
$bank=$rowo['bank'];
$bank = mb_convert_encoding($bank,"utf-8","Windows-1251");
$rs=$rowo['rs'];
$ks=$rowo['ks'];
$bik=$rowo['bik'];
$ogron=$rowo['ogron'];
$okpo=$rowo['okpo'];
$inn=$rowo['inn'];
$kpp=$rowo['indeks'];
$phone=$rowo['phone'];
$adres1=$rowo['adres1'];
$adres1 = mb_convert_encoding($adres1,"utf-8","Windows-1251");
$city=$rowo['city'];
$adres2=$rowo['adres2'];
$adres2 = mb_convert_encoding($adres2,"utf-8","Windows-1251");
$mail=$rowo['mail'];
$orgmail=$rowo['mail'];




$xl = 0;
if ($knigi != 0) {$xl++;$knigitr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">Трудовая книжка</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$knigi.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">шт</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$cenaknigi.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$summaknigi.'</TD>
</TR>';} else {$knigitr='';}
if ($vklady != 0) {$xl++;$vkladytr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">Вкладыш в трудовую книжку</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$vklady.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">шт</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$cenavklady.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$summavklady.'</TD>
</TR>';} else {$vkladytr='';}
if ($uchet != 0) {$xl++;$uchettr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">Книги учета</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$uchet.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">шт</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$priceuch.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$summauch.'</TD>
</TR>';} else {$uchettr='';}
if ($korob != 0) {$xl++;$korobtr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">Короба-боксы</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$korob.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">шт</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$pricekor.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$summakor.'</TD>
</TR>';} else {$korobtr='';}
if ($rashod != 0) {$xl++;$rashodtr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">Приходно-расходные книги</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$rashod.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">шт</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$priceras.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$summaras.'</TD>
</TR>';} else {$rashodtr='';}

//блок доставки
if ($dost != 0) {
$city = mb_convert_encoding($city,"utf-8","Windows-1251");
if ($dost == 1) {$dostlabel='Доставка почтой';}
else {$dostlabel='Доставка курьером';}
$summa=$summa+$sumdost;
$nds = round(($summa-($summa/1.18)), 2);
$textsumm = num2str($summa);
$xl++;
$dosttr='
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$xl.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;">'.$dostlabel.'</TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center">'.$sumdost.'</TD>
</TR>';}
else {$dosttr='';
$city = mb_convert_encoding($city,"utf-8","Windows-1251");}
//конец блока доставки







$text='<br>
<br>
<table cellpadding=0 cellspacing=0 width="632px">
<TR><TD align="center"><small><b><u>Внимание!</u></b> Оплата данного счета означает согласие с условиями поставки товара.
Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.
Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.</small>
</TD></TR></table>
<TABLE width="632px" style="border-right: #000000 1px solid; border-left: #000000 1px solid;  border-top: #000000 1px solid; border-collapse:collapse; margin-top: 10px">
<TR>
	<TD width="370px" style="padding-left: 3px; vertical-align: top">ОАО Сбербанк России г.Москва</TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; padding-left: 3px; vertical-align: top" align="center">БИК</TD>
	<TD width="202px" style="padding-left: 3px;" align="center">044525225</TD>
</TR>
<TR>
	<TD width="370px" style="padding-left: 3px;">&nbsp;</TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid;  border-top: #000000 1px solid; padding-left: 3px;" align="center">Сч. №</TD>
	<TD width="202px" style="border-top: #000000 1px solid; padding-left: 3px;" align="center">30101810400000000225</TD>
</TR>
<TR>
	<TD width="370px" style="padding-left: 3px;"><small>Банк получателя</small></TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; padding-left: 3px;">&nbsp;</TD>
	<TD width="202px" style="padding-left: 3px;">&nbsp;</TD>
</TR>
</TABLE>
<TABLE width="632px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-top: #000000 1px solid; border-collapse:collapse">
<TR>
	<TD width="60px" style="border:1px solid black; padding-left: 3px;" align="center">ИНН</TD>
	<TD width="125px" style="border:1px solid black; padding-left: 3px;" align="center">7716508694</TD>
	<TD width="60px" style="border:1px solid black; padding-left: 3px;" align="center">КПП</TD>
	<TD width="125px" style="border:1px solid black; padding-left: 3px;" align="center">771601001</TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; padding-left: 3px;" align="center">Сч. №</TD>
	<TD width="202px" style="padding-left: 3px;" align="center">40702810538260106530</TD>
</TR>
</TABLE>
<TABLE width="632px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid; border-collapse:collapse">
<TR>
	<TD width="370px" style=" padding-left: 3px;">Общество с ограниченной ответственностью "Имидж"<br>
    Московский банк Сбербанка России ОАО<br>
    <small>Получатель</small></TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; padding-left: 3px;">&nbsp;</TD>
	<TD width="202px" style="padding-left: 3px;">&nbsp;</TD>
</TR>
</table>
<table cellpadding=0 cellspacing=0 width="632px">
<TR><TD colspan="5">&nbsp;</TD></TR>
<TR>
	<TD width="140px"><b><big>Счет на оплату №</big></b></TD>
	<TD width="60px" align="center"><b><big>'.$number.'</big></b></TD>
	<TD width="10px"><b><big>от</big></b></TD>
	<TD width="100px" align="center"><b><big>'.$date.'</big></b></TD>
    <TD width="322px"><b><big>&nbsp;</big></b></TD>
</TR>
<TR><TD colspan="5"><HR style="border: 0; color: black; background-color: black; height: 2px; width: 630px;"></TD></TR>
</TABLE>

<TABLE cellpadding=2 cellspacing=0 width="632px">
<TR>
<TD valign="top">Поставщик:</TD>
<TD>ИНН 7716508694, КПП 771601001, ООО "ИМИДЖ", 129337, Москва г, Ярославское ш.,
<NOBR>дом № 111,</NOBR><NOBR> корпус 2,</NOBR><NOBR> кв.252,</NOBR> тел.: <NOBR>(499)707-17-91, (800)555-80-54,</NOBR> факс: <NOBR>(495)647-10-70</NOBR></TD>
</TR>
<TR><TD valign="top">Покупатель:</TD>
<TD>ИНН '.$inn.', КПП '.$kpp.', '.$orgname.', '.$adres1.', тел.: <NOBR>'.$phone.'</NOBR></TD>
</TR>
</TABLE>

<TABLE cellpadding=2 cellspacing=0 width="632px" style="border:1px solid black;border-collapse:collapse; margin-top: 10px">
<TR>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b>№</b></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b>Товар</b></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b><NOBR>Кол-во</NOBR></b></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b>Ед.</b></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b>Цена</b></TD>
	<TD style="border:1px solid black; padding-left: 3px;" align="center"><b>Сумма</b></TD>
</TR>

// НАЧАЛО ЗАМЕНЫ ТАБЛИЦЫ
'.$knigitr.$vkladytr.$uchettr.$korobtr.$rashodtr.$dosttr.'
// КОНЕЦ ЗАМЕНЫ ТАБЛИЦЫ

</TABLE>
<TABLE cellpadding=3 cellspacing=0 width="632px">
<TR><TD width="500px" align="right"><b><big>Итого:</big></b></TD>
<TD align="right"><u><b><big>'.$summa.'</big></b></u></TD></TR>
<TR><TD width="500px" align="right"><b><big>В том числе НДС 18%:</big></b></TD>
<TD align="right"><b><big>'.$nds.'</big></b></TD></TR>
<TR><TD>Всего '.$all.' наименований, на сумму '.$textsumm.'</TD><TD></TD></TR>
</table>
<TABLE cellpadding=2 cellspacing=0 width="632px">
<TR><TD><HR style="border: 0; color: black; background-color: black; height: 2px; width: 630px;"><BR></TD></TR>
</TABLE>

<TABLE cellpadding=0 cellspacing=0 width="632px">
<TR>
	<TD width="80px">Руководитель</TD>
	<TD width="100px" style="border-bottom:1px solid black;">&nbsp;</TD>
	<TD width="100px">Конторичев О. А.</TD>
	<TD width="120px">&nbsp;</TD>
	<TD width="60px">Бухгалтер</TD>
	<TD width="100px" style="border-bottom:1px solid black;">&nbsp;</TD>
	<TD width="100px">Конторичев О. А.</TD>
</TR>
</TABLE>
<BR>
 <BR>
<small><u>Примечание.</u>
Счет составлен электронно и действителен без подписи. Оригинал счета будет доставлен вместе с товаром.</small>
<BR>';

// if ($step == '1') {$text.=''.$sostavlen.''. "\r\n";}
// if ($step == '2') {$text.=''.$otpravlen.''. "\r\n";}
// if ($step == '3') {$text.=''.$prinat_na.''. "\r\n";}
//$text.='</h2><h3>'.$name.' - '.$u_mail.'</h3>'. "\r\n";

include("../MPDF54/mpdf.php");
//$mpdf=new mPDF('c','A4','cp1251','',32,25,27,25,16,13);
$mpdf = new mPDF('utf-8', 'A4', '8', '',32,25,45,10, 10, 10);
//$mpdf=new mPDF();
//$mpdf->charset_in = 'cp1251';
$mpdf->SetAutoFont();
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($text);

//$mpdf->Output();
$mpdf->Output('./pdf/zakaz'.$IDzak.'.pdf','F');

//НАЧАЛО КОНСТРУИРОВАНИЯ ДОК
$xl = 0;
if ($knigi != 0) {$xl++;$knigitr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>Трудовая книжка</TD>
	<TD>'.$knigi.'</TD>
	<TD>шт</TD>
	<TD>'.$cenaknigi.'</TD>
	<TD>'.$summaknigi.'</TD>
</TR>';} else {$knigitr='';}
if ($vklady != 0) {$xl++;$vkladytr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>Вкладыш в трудовую книжку</TD>
	<TD>'.$vklady.'</TD>
	<TD>шт</TD>
	<TD>'.$cenavklady.'</TD>
	<TD>'.$summavklady.'</TD>
</TR>';} else {$vkladytr='';}
if ($uchet != 0) {$xl++;$uchettr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>Книги учета</TD>
	<TD>'.$uchet.'</TD>
	<TD>шт</TD>
	<TD>'.$priceuch.'</TD>
        <TD>'.$summauch.'</TD>
</TR>';} else {$uchettr='';}
if ($korob != 0) {$xl++;$korobtr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>Короба-боксы</TD>
	<TD>'.$korob.'</TD>
	<TD>шт</TD>
	<TD>'.$pricekor.'</TD>
	<TD>'.$summakor.'</TD>
</TR>';} else {$korobtr='';}
if ($rashod != 0) {$xl++;$rashodtr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>Приходно-расходные книги</TD>
	<TD>'.$rashod.'</TD>
	<TD>шт</TD>
	<TD>'.$priceras.'</TD>
	<TD>'.$summaras.'</TD>
</TR>';} else {$rashodtr='';}
//новый блок
if ($dost != 0) {
$xl++;
$p22='Доставка товара осуществляется по соглашению сторон по указанной в п.п. 1.1 стоимости по адресу '.$city.', '.$adres2.'.<br>';
$dosttr='
<TR>
	<TD>'.$xl.'</TD>
	<TD>'.$dostlabel.'</TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD>'.$sumdost.'</TD>
</TR>';}
else {
$dosttr='';
$data_dost = mysql_query (" SELECT * FROM terminal WHERE ID = '$extra'") or die (mysql_error());
$dost_ob = mysql_fetch_array($data_dost);
$ppcity=$dost_ob['city'];
$ppadres=$dost_ob['adres'];
$ppcity = mb_convert_encoding($ppcity,"utf-8","Windows-1251");
$ppadres = mb_convert_encoding($ppadres,"utf-8","Windows-1251");
$p22='Покупатель обязуется забрать товар по адресу: г. '.$ppcity.', '.$ppadres.'.<br>';}
//конец нового блока, перевод кодировки города
$summatr='
<TR>
	<TD></TD>
	<TD>Итого</TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD>'.$summa.'</TD>
</TR>';

//расчет даты
$date_time_array = getdate( time() );
$day = $date_time_array['mday'];
$month = $date_time_array['mon'];
if ($month == 1) {$month='января';}
elseif ($month == 2) {$month='февраля';}
elseif ($month == 3) {$month='марта';}
elseif ($month == 4) {$month='апреля';}
elseif ($month == 5) {$month='мая';}
elseif ($month == 6) {$month='июня';}
elseif ($month == 7) {$month='июля';}
elseif ($month == 8) {$month='августа';}
elseif ($month == 9) {$month='сентября';}
elseif ($month == 10) {$month='октября';}
elseif ($month == 11) {$month='ноября';}
else {$month='декабря';}
//конец расчета даты

if ($osn == 'ust') {$osnovanie='Устава';}
else {$osnovanie='доверенности';}

$doctext='
<html>
<head>
<title>Договор поставки</title>
</head>
<body><div align="center"><b>ДОГОВОР ПОСТАВКИ ТОВАРОВ № '.$IDzak.'<br>от '.$day.' '.$month.' 2015 г.</b></div><br>
'.$orgname.', именуемое в дальнейшем "Покупатель", представителем которого является '.$person.', действующий на основании '.$osnovanie.', с одной стороны, и ООО «Имидж», именуемый в дальнейшем "Поставщик", в лице Генерального директора Конторичева Олега Анатольевича, действующего на основании Устава , с другой стороны, (вместе именуемые "Стороны") заключили настоящий Договор о нижеследующем:

<div align="center"><b><p>1. ПРЕДМЕТ ДОГОВОРА</p></b></div>

1.1. Поставщик обязуется передать, а Покупатель принять и оплатить товары:<br><br>
<table border="1" width="100%" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-top: #000000 1px solid; border-collapse:collapse">
<tr>
<td>№<td> Товар <td>Кол-во <td>Ед. <td>Цена <td>Сумма
</tr>
'.$knigitr.$vkladytr.$uchettr.$korobtr.$rashodtr.$dosttr.$summatr.'
</table>
<br>
1.2. Товар принадлежит Поставщику на праве собственности, не заложен, не арестован, не является предметом исков третьих лиц.
<div align="center"><b><p>2. УСЛОВИЯ ПОСТАВКИ ТОВАРА</p></b></div>
2.1. Поставщик гарантирует, что качество поставляемых товаров соответствует требованиям, установленным законодательством Российской Федерации.<br>
2.2. '.$p22.'
2.3. Покупатель имеет право вернуть Поставщику товар, имеющий явный производственный брак, в течение 14 (четырнадцати) дней с момента приема-передачи товара. Возврат брака оформляется Покупателем с выдачей Поставщику акта об обнаружении производственного брака, подписанный уполномоченной комиссией, товарно-транспортной накладной и счета-фактуры типового образца.<br>

2.4. После приемки товара представителем покупателя или после передачи транспортной компании для дальнейшей транспортировки к покупателю, ответственность за дефекты товара возникшие при транспортировке лежит на Покупателе.

<div align="center"><b><p>3. ЦЕНА ТОВАРА И ПОРЯДОК РАСЧЕТОВ</p></b></div>

3.1. Цена товара устанавливается в валюте РФ и указывается в счете, накладных, счетах-фактурах, являющихся неотъемлемой частью настоящего Договора.<br>

3.2. Покупатель обязуется произвести 100% (стопроцентную) предоплату за заказанный товар.

<div align="center"><b><p>4. ПРАВА И ОБЯЗАННОСТИ СТОРОН</p></b></div>

4.1. Поставщик обязан:<br>

4.1.1. Передать Покупателю товар надлежащего качества, количества и ассортимента.<br>

4.2. Покупатель обязан:<br>

4.2.1. Осуществить проверку при приемке товара по количеству, качеству и ассортименту и подписать соответствующую накладную или передать Покупателю доверенность на право получения указанного в договоре товара своим представителем. В случае если доставку товара осуществляет уполномоченная организация, отправить подписанные экземпляры документов, предназначенные для Продавца, по почте или курьерской службой на фактический адрес Продавца, указанный в договоре.<br>

4.2.2. Подписанная Покупателем накладная или представленная Продавцу доверенность представителем Покупателя является подтверждением выполнения Продавцом своих договорных обязательств. При доставке товара транспортной компанией, курьерской службой или иной службой доставки, свидетельством выполнения Продавцом своих обязательств является документ о передаче товара для доставки Покупателю.<br>

4.2.3. Покупатель обязан производить проверку сопровождающих документов при приемке товаров.


<div align="center"><b><p>5. СРОК ДЕЙСТВИЯ ДОГОВОРА</p></b></div>

5.1. Настоящий Договор вступает в силу с момента его подписания и действует до полного исполнения сторонами своих обязательств.

<div align="center"><b><p>6. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</p></b></div>

6.1. Все споры и разногласия, которые могут возникнуть из настоящего Договора или в связи с ним, разрешаются путем переговоров, а при недостижении согласия –рассматривается в арбитражном суде г.Москвы.<br>

6.2. Все изменения и дополнения к Договору совершаются только в письменной форме в виде приложений к настоящему Договору и подлежат подписанию обеими сторонами.<br>

6.3. Настоящий Договор составлен в двух экземплярах, по одному для каждой из сторон.<br>

<div align="center"><b><p>7. АДРЕСА И БАНКОВСКИЕ РЕКВИЗИТЫ СТОРОН</p></b></div>

<table align="center" width="100%" border="1" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-top: #000000 1px solid; border-collapse:collapse">
<tr>
<td width="50%">Покупатель - '.$orgname.'<td width="50%">Поставщик – ООО «Имидж»
</tr>
<tr>
<td valign="top">Юр.адрес: '.$adres1.'<br>Тел.:'.$phone.'<td>Юр. адрес.: 129337, г. Москва, Ярославское ш., д. 111,корп.2, кВ. 252 <br>Факт.адр.: 123995, г.Москва, Бережковская наб., д.20, стр.28
</tr>
<tr>
<td>Р/с <b>'.$rs.'</b><td>Р/c <b>40702810538260106530</b>
</tr>
<tr>
<td>'.$bank.'<td>Сбербанк России г. Москва 
</tr>
<tr>
<td>К/с <b>'.$ks.'</b><td>К/с <b>30101810400000000225</b>
</tr>
<tr>
<td>БИК <b>'.$bik.'</b><br>ОКПО <b>'.$okpo.'</b><td>БИК <b>044525225</b><br>ОКПО <b>73799778</b>
</tr>
<tr>
<td>ИНН <b>'.$inn.'</b><br>КПП <b>'.$kpp.'</b><td>ИНН <b>7716508694</b><br> КПП <b>771601001</b>
</tr>
</table>

<table width="100%">
<tr>
<td width="50%" height="75">Покупатель <br><br><br><td height="75">Поставщик<br><br><br>
</tr>
<tr>
<td>/ ____________________ / ______________ <td>/ ___________________ / Конторичев О.А.
</tr>
</table>
</body>
</html>';

$docname = 'dogovor.html';
// Пишем содержимое в файл
file_put_contents($docname, $doctext);
$docname='./doc/dogovor'.$IDzak.'';
// подключим файл
include( 'html_to_doc.inc.php' );
// конструируем экземпляр
$file = new HTML_TO_DOC();
// конвертим url
//$file->createDocFromURL("http://trudknigi.ru/dogovor.html", "output", false );
// либо файл на диске
$file->createDoc( "dogovor.html", $docname, false );
//КОНЕЦ КОНСТРУИРОВАНИЯ ДОК



//НАЧАЛО ОТПРАВКИ

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}


$mail=$rowo['mail'];

//начало формирования текста

if ($dost == 0){$part = 'При оплате заказа по безналичному расчету, после поступления оплаты Вы можете забрать заказ в рабочие дни по адресу г. '.$ppcity.', '.$ppadres.'.  Для получения заказа необходима доверенность  от организации или печать организации (для оформления накладных на заказ) и заполненный в двух экземплярах договор, приложенный к этому письму.<br>';}
if ($dost == 1){$part = 'При оплате заказа по безналичному расчету, после поступления оплаты мы отправим Вам заказ почтой по адресу '.$city.', '.$adres2.'. Вместе с заказом Вы получите пакет всех необходимых документов в двух экземплярах, один из которых необходимо заполнить и переслать на обратный адрес (п. 4.2 Договора).';}
if ($dost == 2){$part = 'При оплате заказа по безналичному расчету, после поступления оплаты наш курьер (курьерская служба) доставит Ваш заказ по адресу '.$city.', '.$adres2.' по рабочим дням.  Вы можете также оплатить заказ за наличный расчет нашему курьеру, который выдаст Вам кассовый чек. Для получения заказа необходима доверенность  от организации или печать организации (для оформления накладных на заказ) и заполненный наш экземпляр договора, приложенный к этому письму. Ваш экземпляр договора привезет курьер.<br>';}


$mailfrom = "Automail to ".$mail;
$subj = 'Заказ №'.$IDzak.'';
$text = '<h3>Заказ на сайте trudknigi.ru</h3>
<h3>Automail to '.$mail.'</h3>

<p>От Вашей организации получен заказ и зарегистрирован под номером '.$IDzak.'. В приложении к данному письму Вы найдете договор и счет, сформированные согласно Вашему заказу. 
Внимательно ознакомьтесь с данными документами и заполните договор со своей стороны.</p>

<p>'.$part.'</p>



<p>Это письмо сгенерировано автоматически, отвечать на него не нужно!</p>
ООО "Имидж"<br>
тел: 8(800) 555-80-54<br>8(499) 707-17-91<br>
e-mail: <a href=mailto:imige@imige.ru>imige@imige.ru</a><br>
сайт: <a href=http://trudknigi.ru>trudknigi.ru</a><br>';

//конец формирования текста



require_once('./PHPMailer/class.phpmailer.php');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail->IsSendmail(); // telling the class to use SendMail transport

if ($target==2) {
try {
  $mail->AddAddress($orgmail, $orgname);
  $mail->SetFrom('no_reply@imige.ru', $mailfrom);
//  $mail->AddReplyTo('name@yourdomain.com', 'First Last');
  $mail->Subject = $subj;
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($text);
  $mail->AddAttachment('./pdf/zakaz'.$IDzak.'.pdf');      // attachment
  $mail->AddAttachment('./doc/dogovor'.$IDzak.'.doc');      // attachment
  $mail->Send();
//  echo 'Message Sent OK<br>';
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}

$otpravka = mysql_query ("SELECT * FROM otpravka") or die( mysql_error());
$num_otpr = mysql_num_rows($otpravka);
$i=0;

while ($i < $num_otpr) {
sleep(4);
$ob_korz = mysql_fetch_object($otpravka);
$ob_mail=$ob_korz->mail;
$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
$mail->IsSendmail(); // telling the class to use SendMail transport
try {
  $mail->AddAddress($ob_mail, '');
  $mail->SetFrom('no_reply@imige.ru', 'Dont reply this mail');
  $mail->Subject = $subj;
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML($text);
  $mail->AddAttachment('./pdf/zakaz'.$IDzak.'.pdf');      // attachment
  $mail->AddAttachment('./doc/dogovor'.$IDzak.'.doc');      // attachment
  $mail->Send();
//  echo 'Message Sent OK<br>';
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
echo $e->getMessage(); //Boring error messages from anything else!
}

$i++;
}

echo '<html>
 <head>
  <title>Отправка документов</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
 </head> 
<body>
<h1>Готово!</h1>
</body>
</html>';

}

?>