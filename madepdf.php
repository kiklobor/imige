<?php
require_once ( 'setup.php');
require_once ( 'fukc_num_str.php');
//достаем цены на бланки
include('../tkGeneral/prg_takePrices.php');

$IDzak=$_GET['IDzak'];
$data_zak = mysql_query (" SELECT * FROM zakaz WHERE ID = '$IDzak'") or die (mysql_error());
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

if ($uchet>0) {
    $data_uch = mysql_query (" SELECT * FROM prices WHERE position = 'uchet'") or die (mysql_error());
    $rowu = mysql_fetch_array($data_uch);
    $priceuch=$rowu['price'];
    $summauch=$uchet*$priceuch;
    }
if ($korob>0) {
    $data_kor = mysql_query (" SELECT * FROM prices WHERE position = 'korob'") or die (mysql_error());
    $rowk = mysql_fetch_array($data_kor);
    $pricekor=$rowk['price'];
    $summakor=$korob*$pricekor;
    }
if ($rashod>0) {
    $data_ras = mysql_query (" SELECT * FROM prices WHERE position = 'rashod'") or die (mysql_error());
    $rowr = mysql_fetch_array($data_ras);
    $priceras=$rowr['price'];
    $summaras=$rashod*$priceras;
    }

if ($knigi<1) {unset($cenaknigi);unset($summaknigi);}
if ($knigi==1) {$cenaknigi = $prices['tk1']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>1 AND $knigi<5) {$cenaknigi = $prices['tk2']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>4 AND $knigi<10) {$cenaknigi = $prices['tk3']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>9 AND $knigi<25) {$cenaknigi = $prices['tk4']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>24 AND $knigi<101) {$cenaknigi = $prices['tk5']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>100 AND $knigi<250) {$cenaknigi = $prices['tk6']; $summaknigi = $cenaknigi*$knigi;}
if ($knigi>249) {$cenaknigi = $prices['tk7']; $summaknigi = $cenaknigi*$knigi;}

if ($vklady<1) {unset($cenavklady);unset($summavklady);}
if ($vklady==1) {$cenavklady = $prices['vtk1']; $summavklady = $cenavklady*$vklady;}
if ($vklady>1 AND $vklady<5) {$cenavklady = $prices['vtk2']; $summavklady = $cenavklady*$vklady;}
if ($vklady>4 AND $vklady<10) {$cenavklady = $prices['vtk3']; $summavklady = $cenavklady*$vklady;}
if ($vklady>9 AND $vklady<25) {$cenavklady = $prices['vtk4']; $summavklady = $cenavklady*$vklady;}
if ($vklady>24 AND $vklady<101) {$cenavklady = $prices['vtk5']; $summavklady = $cenavklady*$vklady;}
if ($vklady>100 AND $vklady<250) {$cenavklady = $prices['vtk6']; $summavklady = $cenavklady*$vklady;}
if ($vklady>249) {$cenavklady = $prices['vtk7']; $summavklady = $cenavklady*$vklady;}

$date = date("d.m.Y");
$number = str_pad($IDzak, 5, "0", STR_PAD_LEFT);
$all = $knigi + $vklady + $korob + $rashod + $uchet;
$summa = $summaknigi + $summavklady + $summakor + $summauch + $summaras;
$nds = round(($summa-($summa/1.2)), 2);
$textsumm = num2str($summa);

$data_org = mysql_query (" SELECT * FROM orglist WHERE ID = '$IDorg'") or die (mysql_error());
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


//основание
if ($osn=='ust') {$osn='Устава';}
else {$osn='доверенности';}

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
$nds = round(($summa-($summa/1.2)), 2);
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
	<TD width="370px" style="padding-left: 3px; vertical-align: top">ПАО Сбербанк России г.Москва</TD>
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
	<TD width="125px" style="border:1px solid black; padding-left: 3px;" align="center">503001001</TD>
	<TD width="60px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; padding-left: 3px;" align="center">Сч. №</TD>
	<TD width="202px" style="padding-left: 3px;" align="center">40702810538260106530</TD>
</TR>
</TABLE>
<TABLE width="632px" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid; border-collapse:collapse">
<TR>
	<TD width="370px" style=" padding-left: 3px;">Общество с ограниченной ответственностью "Имидж"<br>
    Московский банк Сбербанка России ПАО<br>
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
<TD>ИНН 7716508694, КПП 503001001, ООО "ИМИДЖ", 143306, МО, <NOBR>Наро-Фоминский район,</NOBR> <NOBR>г. Наро-Фоминск,</NOBR> <NOBR>ул. Ленина, д. 28, офис 1, этаж 1</NOBR>
тел.: <NOBR>(499)707-17-91, (800)555-80-54,</NOBR></TD>

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
<TR><TD width="500px" align="right"><b><big>В том числе НДС 20%:</big></b></TD>
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
	<TD width="100px">Конторичев О.А.</TD>
	<TD width="120px">&nbsp;</TD>
	<TD width="60px">Бухгалтер</TD>
	<TD width="100px" style="border-bottom:1px solid black;">&nbsp;</TD>
	<TD width="100px">Конторичев О.А.</TD>
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

include("./MPDF54/mpdf.php");
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
$day = date("d");
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
$year = date("Y");
//конец расчета даты

$doctext='
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Договор поставки</title>
</head>
<body><div align="center"><b>ДОГОВОР ПОСТАВКИ ТОВАРОВ № '.$IDzak.'<br>от '.$day.' '.$month.' '.$year.' г.</b></div><br>

'.$orgname.', именуемое в дальнейшем "Покупатель", представителем которого является '.$person.', действующий на основании '.$osn.', с одной стороны, и ООО «Имидж», именуемый в дальнейшем "Поставщик", в лице Генерального директора Конторичева Олега Анатольевича, действующего на основании Устава , с другой стороны, (вместе именуемые "Стороны") заключили настоящий Договор о нижеследующем:

<div align="center"><b><p>1. ПРЕДМЕТ ДОГОВОРА</p></b></div>

1.1. Поставщик обязуется передать, а Покупатель принять и оплатить товары:<br><br>
<table border="1" width="100%" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-top: #000000 1px solid; border-collapse:collapse">
<tr>
<td>№<td> Товар <td>Кол-во <td>Ед. <td>Цена <td>Сумма
</tr>
'.$knigitr.$vkladytr.$uchettr.$korobtr.$rashodtr.$dosttr.$summatr.'
</table>
<br>
Сумма указана  с учетом НДС - 20%.
1.2. Товар принадлежит Поставщику на праве собственности, не заложен, не арестован, не является предметом исков третьих лиц.
<div align="center"><b><p>2. УСЛОВИЯ ПОСТАВКИ ТОВАРА</p></b></div>
2.1. Поставщик гарантирует, что качество поставляемых товаров соответствует требованиям, установленным законодательством Российской Федерации.<br>
2.2. '.$p22.'
2.3. Покупатель имеет право вернуть Поставщику товар, имеющий явный производственный брак, в течение 14 (четырнадцати) дней с момента приема-передачи товара. Возврат брака оформляется Покупателем с выдачей Поставщику акта об обнаружении производственного брака, подписанный уполномоченной комиссией, товарно-транспортной накладной и счета фактуры типового образца.<br>

2.4. После приемки товара представителем покупателя или после передачи транспортной компании для дальнейшей транспортировки к покупателю, ответственность за дефекты товара возникшие при транспортировке лежит на Покупателе.

<div align="center"><b><p>3. ЦЕНА ТОВАРА И ПОРЯДОК РАСЧЕТОВ</p></b></div>

3.1. Цена товара устанавливается в валюте РФ и указывается в счете, накладных, счетах-фактурах, являющихся неотъемлемой частью настоящего Договора.<br>

3.2. Покупатель обязуется произвести 100% (стопроцентную) предоплату за заказанный товар.

<div align="center"><b><p>4. ПРАВА И ОБЯЗАННОСТИ СТОРОН</p></b></div>

4.1. Поставщик обязан:<br>

4.1.1. Передать Покупателю товар надлежащего качества, количества и ассортимента.<br>

4.2. Покупатель обязан:<br>

4.2.1. Осуществить проверку при приемке товара по количеству, качеству и ассортименту и подписать соответствующую накладную или передать Покупателю доверенность на право получения указанного в договоре товара своим представителем. В случае если доставку товара осуществляет уполномоченная организация, отправить подписанные экземпляры документов, предназначенные для Продавца, по почте или курьерской службой на фактический адрес Продавца, указанный в договоре.<br>

4.2.2. Подписанная Покупателем накладная или представленная Продавцу доверенность представителем Покупателя является подтверждением выполнения Продавцом своих договорных обязательств. При доставке товара транспортной компанией, курьерской службой или иной службой доставки, свидетельством выполнения Продавцом своих обязательств является документ о передачи товара для доставки Покупателю.<br>

4.2.3. Покупатель обязан производить проверку сопровождающих документов при приемке товаров.


<div align="center"><b><p>5. СРОК ДЕЙСТВИЯ ДОГОВОРА</p></b></div>

5.1. Настоящий Договор вступает в силу с момента его подписания и действует до полного исполнения сторонами своих обязательств.

<div align="center"><b><p>6. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</p></b></div>

6.1. Все споры и разногласия, которые могут возникнуть из настоящего Договора или в связи с ним, разрешаются путем переговоров, а при не достижении согласия –рассматривается в арбитражном суде г.Москвы.<br>

6.2. Все изменения и дополнения к Договору совершаются только в письменной форме в виде приложений к настоящему Договору и подлежат подписанию обеими сторонами.<br>

6.3. Настоящий Договор составлен в двух экземплярах, по одному для каждой из сторон.<br>

<div align="center"><b><p>7. АДРЕСА И БАНКОВСКИЕ РЕКВИЗИТЫ СТОРОН</p></b></div>

<table align="center" width="100%" border="1" style="border-right: #000000 1px solid; border-left: #000000 1px solid; border-top: #000000 1px solid; border-collapse:collapse">
<tr>
<td width="50%">Покупатель - '.$orgname.'<td width="50%">Поставщик – ООО «Имидж»
</tr>
<tr>
<td valign="top">Юр.адрес: '.$adres1.'<br>Тел.:'.$phone.'<td>Юр. адрес.: 143306, МО, Наро-Фоминский район, г. Наро-Фоминск, ул. Ленина, д. 28, офис 1, этаж 1 <br>Факт.адр.: 109428 г. Москва, Ленинский проспект, д.42 корп. 1, помещение XIа, комната 25
</tr>
<tr>
<td>Р/с <b>'.$rs.'</b><td>Р/c <b>40702810538260106530</b>
</tr>
<tr>
<td>'.$bank.'<td>ПАО Сбербанк г. Москва 
</tr>
<tr>
<td>К/с <b>'.$ks.'</b><td>К/с <b>30101810400000000225</b>
</tr>
<tr>
<td>БИК <b>'.$bik.'</b><br>ОКПО <b>'.$okpo.'</b><td>БИК <b>044525225</b><br>ОКПО <b>73799778</b>
</tr>
<tr>
<td>ИНН <b>'.$inn.'</b><br>КПП <b>'.$kpp.'</b><td>ИНН <b>7716508694</b><br> КПП <b>503001001</b>
</tr>
</table>

<table width="100%">
<tr>
<td width="50%" height="50">Покупатель <br><br><br><td height="50">Поставщик<br><br><br>
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

//ФОРМИРОВАНИЕ XML
/*if ($knigi != 0) {$xml1='<Товары Сумма="'.$summaknigi.'" Количество="'.$knigi.'" Номенклатура="Трудовая книжка" Артикул="ТК"/>';} else {$xml1='';}
if ($vklady != 0) {$xml2='<Товары Сумма="'.$summavklady.'" Количество="'.$vklady.'" Номенклатура="Вкладыши в трудовую книжку" Артикул="ВТ"/>';} else {$xml2='';}
if ($korob != 0) {$xml3='<Товары Сумма="'.$summakor.'" Количество="'.$korob.'" Номенклатура="Короб-бокс" Артикул="КТК"/>';} else {$xml3='';}
if ($rashod != 0) {$xml4='<Товары Сумма="'.$summaras.'" Количество="'.$rashod.'" Номенклатура="Приходно-расходная книга" Артикул="ПРК"/>';} else {$xml4='';}
if ($uchet != 0) {$xml5='<Товары Сумма="'.$summauch.'" Количество="'.$uchet.'" Номенклатура="Книга учета" Артикул="КУД"/>';} else {$xml5='';}

$timedate = date("d.m.Y H:i:s");
$xmlname='./xmls/zakaz'.$IDzak.'.xml';

$xmltext='<?xml version="1.0" encoding="UTF-8"?>
<Корневой ЗаказыССайта="Заказ с trudknigi.ru">
<Документ Договор="Договор №'.$IDzak.'" Сумма="'.$summa.'" Дата="'.$timedate.'" Номер="'.$IDzak.'" ИНН="'.$inn.'" КПП="'.$kpp.'" БИК="'.$bik.'" РасчетныйСчет="'.$rs.'" БанкКоррСчет="'.$ks.'" Телефон="'.$phone.'">
<Контрагент>'.$orgname.'</Контрагент>
<ЮридическийАдрес>'.$adres1.'</ЮридическийАдрес>
<ФактическийАдрес>'.$city.', '.$adres2.'</ФактическийАдрес>
<БанкНаименование>'.$bank.'</БанкНаименование>
'.$xml1.$xml2.$xml3.$xml4.$xml5.'</Документ></Корневой>';
$fp = fopen($xmlname, "w");
fwrite($fp, $xmltext);
fclose ($fp);*/
//КОНЕЦ XML

$url = '/sentpdf.php?IDzak='.$IDzak.'&IDorg='.$IDorg.'';
header("Location: $url");

?>