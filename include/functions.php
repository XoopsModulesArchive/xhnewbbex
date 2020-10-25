<?php

function xhnewbbex_createmeta_keywords($content)
{
    $tmp = [];

    // Search for the Minimum keyword length

    $configHandler = xoops_getHandler('config');

    $xoopsConfigSearch = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);

    $limit = $xoopsConfigSearch['keyword_min'];

    $xoopsConfigMeta = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);

    $syskeywords = $xoopsConfigMeta['meta_keywords'];

    $myts = MyTextSanitizer::getInstance();

    $content = $myts->undoHtmlSpecialChars(strip_tags($content));

    $content = mb_strtolower($content);

    $content = str_replace('&nbsp;', ' ', $content);

    $content = str_replace("\t", ' ', $content);

    $content = str_replace("\r\n", ' ', $content);

    $content = str_replace("\r", ' ', $content);

    $content = str_replace("\n", ' ', $content);

    $content = str_replace(',', ' ', $content);

    $content = str_replace('.', ' ', $content);

    $content = str_replace(';', '', $content);

    $content = str_replace(':', '', $content);

    $content = str_replace(')', '', $content);

    $content = str_replace('(', '', $content);

    $content = str_replace('"', '', $content);

    $content = str_replace('?', '', $content);

    $content = str_replace('!', '', $content);

    $content = str_replace('{', '', $content);

    $content = str_replace('}', '', $content);

    $content = str_replace('[', '', $content);

    $content = str_replace(']', '', $content);

    $content = str_replace('<', '', $content);

    $content = str_replace('>', '', $content);

    $content = str_replace("'", ' ', $content);

    $keywords = explode(' ', $content);

    $keywords = array_unique($keywords);

    //var_dump($keywords);

    foreach ($keywords as $keyword) {
        if (mb_strlen($keyword) >= $limit && !is_numeric($keyword)) {
            $tmp[] = $keyword;
        }
    }

    if (count($tmp) > 0) {
        $result = implode(',', $tmp);

        //if(strlen($result)<strlen($syskeywords)) {

        // return $syskeywords;

        //} else {

        return $result;
        //}
    }
  

    return '';
}
