<?php
/**
 * Portuguese (Portugal) language pack
 * @package modules: faqs
 * @subpackage i18n
 */

i18n::include_locale_file('modules: ssrating', 'en_US');

global $lang;

if(array_key_exists('pt_PT', $lang) && is_array($lang['pt_PT'])) {
	$lang['pt_PT'] = array_merge($lang['en_US'], $lang['pt_PT']);
} else {
	$lang['pt_PT'] = $lang['en_US'];
}

$lang['pt_PT']['SSRatingExtension']['RANKINGTITLE'] = 'Classificação';
$lang['pt_PT']['SSRatingExtension']['RATINGTITLE'] = 'Classificação';

$lang['pt_PT']['SSRatingExtension']['STAR1'] = 'Mau';
$lang['pt_PT']['SSRatingExtension']['STAR2'] = 'Suficiente';
$lang['pt_PT']['SSRatingExtension']['STAR3'] = 'Bom';
$lang['pt_PT']['SSRatingExtension']['STAR4'] = 'Muito Bom';
$lang['pt_PT']['SSRatingExtension']['STAR5'] = 'Excelente';
$lang['pt_PT']['SSRatingExtension']['RATEAVERAGE'] = 'Média: ';
$lang['pt_PT']['SSRatingExtension']['RATESUM'] = 'Votos: ';
$lang['pt_PT']['SSRatingExtension']['STARLABEL'] = 'Classificação ';

$lang['pt_PT']['SSRating.ss']['RATEAVERAGE'] = 'Média: ';
$lang['pt_PT']['SSRating.ss']['RATESUM'] = 'Votos: ';
$lang['pt_PT']['SSRating.ss']['PAGERATE'] = 'Avaliação da página';
$lang['pt_PT']['SSRating.ss']['RATETHISPAGE'] = 'Avalie esta página';

?>