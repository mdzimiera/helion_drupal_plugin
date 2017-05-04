<?php

function kategorie($ksiazka) {
    $kategorie = array();
    if ($ksiazka->serietematyczne)
        foreach ($ksiazka->serietematyczne->seriatematyczna as $kategoria)
            if (!in_array($kategoria, $kategorie))
                array_push($kategorie, $kategoria);
    return $kategorie;
}

function kategorieKsiazekDoWyswietlenia() {


    $retArray = array();
    foreach (array_keys(variable_get("helion_ksiegarnia_kategoria_ksiazki", array("wszystkie" => "wszystkie"))) as $key => $val) {
        @list($wyd, $kat_id) = explode("-", $val);
        if (!isset($retArray[$wyd]))
            $retArray[$wyd] = array();
        array_push($retArray[$wyd], $kat_id);
    }

    return $retArray;
}

function _sqlSearchQueryBuilder($patterns, $field, $doNotUseWords = FALSE) {

    if ($doNotUseWords)
        $patterns = array(join(" ", $patterns));

    $searchParams = '('; 
    foreach ($patterns as $pattern) {
            if (db_driver() == 'pgsql')
                $searchParams .= "LOWER(" . $field . ") ~ '\\\m" . strtolower($pattern) . "\\\M' AND ";
            else
                $searchParams .= "LOWER(" . $field . ") REGEXP '" . strtolower($pattern) . "' AND ";
    }
    $searchParams = rtrim($searchParams, "AND ") . ")";
    return $searchParams;
}

// function _sqlSearchQueryBuilder($patterns, $field, $doNotUseWords = FALSE)

function doForceUpdate($wydawnictwo) {
    if (variable_get("helion_ksiegarnia_error", 0) == false) {
        if ($wydawnictwo == 'wszystkie')
            $wydawnictwo = variable_get('helion_ksiegarnia_losowa_ksiazka_kategoria', 'helion');
        include_once drupal_get_path("module", "helion_ksiegarnia") . "/" . 'helion_ksiegarnia_update.php';
        $helion_ksiegarnia_update = new helion_ksiegarnia_update();
        $helion_ksiegarnia_update->updateCategories();
        $helion_ksiegarnia_update->updateBooks();
    }
}

// function doForceUpdate($wydawnictwo) {