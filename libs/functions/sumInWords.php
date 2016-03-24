﻿<?php

function sumInWords($inn, $stripkop = false)
{
	$nol = 'нуль';
	$str[100] = array("", "сто", "двісті", "триста", "чотириста", "п'ятсот", "шістсот", "сімсот", "вісімсот", "дев'ятсот");
	$str[11] = array("", "десять", "одинадцять", "дванадцять", "тринадцять", "чотирнадцять", "п'ятнадцять", "шістнадцять", "сімнадцять", "вісімнадцять", "дев'ятнадцять", "двадцять");
	$str[10] = array("", "десять", "двадцять", "тридцять", "сорок", "п'ятдесят", "шістдесят", "сімдесят", "вісімдесят", "дев'яносто");
	$sex = array(
		array("", "один", "два", "три", "чотири", "п'ять", "шість", "сім", "вісім", "дев'ять"), // m
		array("", "одна", "дві", "три", "чотири", "п'ять", "шість", "сім", "вісім", "дев'ять") // f
	);
	$forms = array(
		array("копійка", "копійки", "копійок", 1), // 10^-2
		array("гривня", "гривні", "гривень", 0), // 10^ 0
		array("тисяча", "тисячі", "тисяч", 1), // 10^ 3
		array("мільйон", "мільйона", "мільйонів", 0), // 10^ 6
		array("мільярд", "мільярда", "мільярдів", 0), // 10^ 9
		array("трильйон", "трильйона", "трильйонів", 0), // 10^12
	);
	$out = $tmp = array();
	// Поехали!
	$tmp = explode('.', str_replace(',', '.', $inn));
	$rub = number_format($tmp[0], 0, '', '-');
	if ($rub == 0)
		$out[] = $nol;
	// нормализация копеек
	$kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0, 2) : '00';
	$segments = explode('-', $rub);
	$offset = sizeof($segments);
	if ((int) $rub == 0) { // если 0 рублей
		$o[] = $nol;
		$o[] = morph(0, $forms[1][0], $forms[1][1], $forms[1][2]);
	} else {
		foreach ($segments as $k => $lev) {
			$sexi = (int) $forms[$offset][3]; // определяем род
			$ri = (int) $lev; // текущий сегмент
			if ($ri == 0 && $offset > 1) {// если сегмент==0 & не последний уровень(там Units)
				$offset--;
				continue;
			}
			// нормализация
			$ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
			// получаем циферки для анализа
			$r1 = (int) substr($ri, 0, 1); //первая цифра
			$r2 = (int) substr($ri, 1, 1); //вторая
			$r3 = (int) substr($ri, 2, 1); //третья
			$r22 = (int) $r2 . $r3; //вторая и третья
			// разгребаем порядки
			if ($ri > 99)
				$o[] = $str[100][$r1]; // Сотни
			if ($r22 > 20) {// >20
				$o[] = $str[10][$r2];
				$o[] = $sex[$sexi][$r3];
			} else { // <=20
				if ($r22 > 9)
					$o[] = $str[11][$r22 - 9]; // 10-20
				elseif ($r22 > 0)
					$o[] = $sex[$sexi][$r3]; // 1-9
			}
			// Рубли
			$o[] = morph($ri, $forms[$offset][0], $forms[$offset][1], $forms[$offset][2]);
			$offset--;
		}
	}
	// Копейки
	if (!$stripkop) {
		$o[] = $kop;
		$o[] = morph($kop, $forms[0][0], $forms[0][1], $forms[0][2]);
	}
	return preg_replace("/\s{2,}/", ' ', implode(' ', $o));
}

/**
 * Склоняем словоформу
 */
function morph($n, $f1, $f2, $f5)
{
	$n = abs($n) % 100;
	$n1 = $n % 10;
	if ($n > 10 && $n < 20)
		return $f5;
	if ($n1 > 1 && $n1 < 5)
		return $f2;
	if ($n1 == 1)
		return $f1;
	return $f5;
}
