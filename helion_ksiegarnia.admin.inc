<?php

/**
 * @file
 * Strona administracyjna
 */
function helion_ksiegarnia_admin_settings() {
    
    $path = drupal_get_path("module", "helion_ksiegarnia") . "/";
    
    $form['helion_ksiegarnia_helion_user_id'] = array(
        '#type' => 'textfield',
        '#title' => t('Numer klienta Programu Partnerskiego HELION:'),
        '#description' => t('Księgarnia nie zostanie aktywowana bez prawidłowego numeru klienta.'),
        '#default_value' => variable_get('helion_ksiegarnia_helion_user_id', ''),
        '#size' => 3
    );

    $wydawnictwa = variable_get('wydawnictwa', array());
    $wydawnictwa_2 = array("wszystkie" => "wszystkie");
    foreach ($wydawnictwa as $wydawnictwo) {
        $wydawnictwa_2[$wydawnictwo] = strtoupper($wydawnictwo);
        $query = db_query("SELECT DISTINCT title,title_id,grupa_id FROM {helion_ksiegarnia_kategorie} WHERE marka = :marka", array(":marka" => $wydawnictwo));

        foreach ($query as $q)
            $wydawnictwa_2[$wydawnictwo . '-' . $q->title_id] = "|" . str_repeat("-", 5) . $q->title;
    }

    $options = array();
    
    foreach ($wydawnictwa_2 as $key => $val) {
        if (!isset($orig_key))
            $orig_key = "";
        
        if (! preg_match("/-/", $key)) {
            $orig_key = $key;    
            $tab[$orig_key] = array();    
        } 
        else
            array_push($tab[$orig_key], $val);
        
    }
    
    array_push($tab["wszystkie"], "wszystkie");
    array_push($options, $tab);

    
    $form['helion_ksiegarnia_kategoria_ksiazki'] = array(
        '#type' => 'select',
        '#title' => t('Kategoria w której znajdują się wyświetlane książki:'),
        '#options' => $wydawnictwa_2,
        '#default_value' => variable_get('helion_ksiegarnia_kategoria_ksiazki', 'wszystkie'),
        '#description' => t('Jeżeli opcja zostanie wybrana, pod uwage przy wyświetlaniu będą brane tylko książki z wybranych kategorii.'),
        '#multiple' => TRUE,
        '#size' => 15
    );
    

    $form['helion_ksiegarnia_ilosc_ksiazek_na_stronie'] = array(
        '#type' => 'textfield',
        '#title' => t('Ilość książek wyświetlanych na stronie:'),
        '#default_value' => variable_get('helion_ksiegarnia_ilosc_ksiazek_na_stronie', '10'),
        '#size' => 3
    );


    $form['helion_ksiegarnia_ebooki'] = array(
        '#type' => 'select',
        '#title' => t('Sposób wyświetlania'),
        '#options' => array('0' => 'wszystkie książki', '1' => 'tylko eBooki'),
        '#description' => t('Umożliwia prezętacje tylko i wyłącznie eBooków lub całości asortymentu.'),
        '#default_value' => variable_get('helion_ksiegarnia_ebooki', '0'),
    );

    $form['helion_ksiegarnia_wyszukiwarka'] = array(
        '#type' => 'select',
        '#title' => t('Wyszukiwarka'),
        '#options' => array('1' => 'wlączona', '0' => 'wyłączona'),
        '#description' => t('Włącza formularz wyszukiwania książek w module księgarni'),
        '#default_value' => variable_get('helion_ksiegarnia_wyszukiwarka', '0'),
    );

    $form['helion_ksiegarnia_dodatkowe_ustawienia'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Dodatkowe ustawienia'),
        '#options' => array(
            'opcja_1' => t('wyświetl wydawnictwa w treści'),
            'opcja_2' => t('wyświetl kategorie w treści'),
        ),
        '#default_value' => variable_get('helion_ksiegarnia_dodatkowe_ustawienia', array("opcja_1", "opcja_2")),
    );

    $form['helion_ksiegarnia_losowa_ksiazka'] = array(
        '#title' => t('Polecana książka / Bestseller / Książka dnia'),
        '#type' => 'fieldset',
        '#description' => t('Ustawienia wyświetlania parametrów polecanej książki, bestsellerów a także książki dnia.')
    );


    $wydawnictwa_1 = array("wszystkie" => t("wszystkie"));
    $wydawnictwa_1 = array_merge($wydawnictwa_1, $wydawnictwa);
    
    $form['helion_ksiegarnia_losowa_ksiazka']['helion_ksiegarnia_losowa_ksiazka_kategoria'] = array(
        '#type' => 'select',
        '#title' => t('Kategoria dotycząca polecanej książki'),
        '#options' => $wydawnictwa_2,
        '#description' => t('Wyświetla polecaną książke ograniczjąc się wyłącznie do wybranej kategori z listy.'),
        '#default_value' => variable_get('helion_ksiegarnia_losowa_ksiazka_kategoria', 'wszystkie'),
    );

    $form['helion_ksiegarnia_losowa_ksiazka']['helion_ksiegarnia_promocja_kategoria'] = array(
        '#type' => 'select',
        '#title' => t('Promocja dnia'),
        '#options' => $wydawnictwa_1,
        '#description' => t('Wyświetla pozycje będącą w promocji dnia ograniczjąc się wyłącznie do wybranej kategori z listy.'),
        '#default_value' => variable_get('helion_ksiegarnia_promocja_kategoria', 'wszystkie'),
    );

    $form['helion_ksiegarnia_losowa_ksiazka']['helion_ksiegarnia_bestseller_kategoria'] = array(
        '#type' => 'select',
        '#title' => t('Bestseller'),
        '#options' => $wydawnictwa_1,
        '#description' => t('Wyświetla bestsellery ograniczjąc się wyłącznie do wybranej kategori z listy.'),
        '#default_value' => variable_get('helion_ksiegarnia_bestseller_kategoria', 'wszystkie'),
    );

    $form['helion_ksiegarnia_losowa_ksiazka']['helion_ksiegarnia_losowa_ksiazka_ustawienia'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Dodatkowe ustawienia wyświetlania'),
        '#options' => array(
            'cena' => t('wyświetl cenę'),
            'tytul' => t('wyświetl tytuł'),
            'koszyk' => t('koszyk'),
        ),
        '#description' => t('Pozwala na załączenie do polecanej książki oprócz okładki takze, cenę, tytuł oraz link do koszyka.'),
        '#default_value' => variable_get('helion_ksiegarnia_losowa_ksiazka_ustawienia', array("cena", "tytul", "koszyk")),
    );

    $form['helion_ksiegarnia_ikony'] = array(
        '#title' => t('Ikony systemowe'),
        '#type' => 'fieldset',
        '#description' => t('Ściezki do plików graficznych używanych w prezentacji księgarni.')
    );


    $form['helion_ksiegarnia_ikony']['helion_ksiegarnia_koszyk'] = array(
        '#type' => 'textfield',
        '#description' => t('Scieżka do pliku graficznego przedstawiającego ikone dodającą przedmiot do koszyka.'),
        '#default_value' => variable_get('helion_ksiegarnia_koszyk', "{$path}gfx/koszszary.jpg"),
        '#size' => 50
    );

    $form['helion_ksiegarnia_ikony']['helion_ksiegarnia_bestseller'] = array(
        '#type' => 'textfield',
        '#description' => t('Scieżka do pliku graficznego przedstawiającego ikone bestsellera.'),
        '#default_value' => variable_get('helion_ksiegarnia_bestseller', "{$path}gfx/bestseller.gif"),
        '#size' => 50
    );

    $form['helion_ksiegarnia_ikony']['helion_ksiegarnia_nowosc'] = array(
        '#type' => 'textfield',
        '#description' => t('Scieżka do pliku graficznego przedstawiającego ikone nowości.'),
        '#default_value' => variable_get('helion_ksiegarnia_nowosc', "{$path}gfx/nowosc.gif"),
        '#size' => 50
    );

    $form['helion_ksiegarnia_ikony']['helion_ksiegarnia_wyszukaj'] = array(
        '#type' => 'textfield',
        '#description' => t('Scieżka do pliku graficznego przedstawiającego ikone klawisza wyszukiwania.'),
        '#default_value' => variable_get('helion_ksiegarnia_wyszukaj', "{$path}gfx/szukaj2.gif"),
        '#size' => 50
    );

    $form['helion_ksiegarnia_cache'] = array(
        '#title' => t('Buforowanie stron'),
        '#type' => 'fieldset',
        '#description' => t('Opcja eksperymentalna, zapisuje odwiedzane strony w specjalnym buforze, dzieki czemu dostep do nich jest duzo szybszy przy nastepnym odwiedzeniu tej samej książki.')
    );    
        
    $form['helion_ksiegarnia_cache']['helion_ksiegarnia_buforowanie'] = array(
        '#type' => 'select',
        '#title' => t('Czy chcesz skorzystać z buforowania ?'),
        '#options' => array(
            '0' => t('nie'),
            '1' => t('tak'),
        ),
        '#default_value' => variable_get('helion_ksiegarnia_buforowanie', 0),
    );    
        
    $form['helion_ksiegarnia_cache']['helion_ksiegarnia_buforowanie_kompresji'] = array(
        '#type' => 'radios',
        '#title' => t('Czy kompresować buforowane pliki tymczasowe ?'),
        '#options' => array(
            t('nie'),
            t('tak'),
        ),
        '#default_value' => variable_get('helion_ksiegarnia_buforowanie_kompresji', 0),
    );
    
    $form['helion_ksiegarnia_cache']['helion_ksiegarnia_wyswietlaj_komunikaty'] = array(
        '#type' => 'select',
        '#title' => t('Debug (tylko Administrator serwisu)'),
        '#options' => array(
            '0' => t('nie'),
            '1' => t('tak'),
        ),
        '#default_value' => variable_get('helion_ksiegarnia_wyswietlaj_komunikaty', 0),
        '#description' => t('Wyświetla komunikaty z informacją czy dana zawartość jest pobierana z bufforu plikowego.')
    );
    
    $form['#submit'][]= 'helion_ksiegarnia_admin_settings_submit';
    
    return system_settings_form($form);
}

function helion_ksiegarnia_admin_settings_validate($form, &$form_state) {
    $user_id = $form_state['values']['helion_ksiegarnia_helion_user_id'];
    if ($user_id == '')
        form_set_error('helion_ksiegarnia_helion_user_id', t('Proszę wprowadzić numer klienta.'));
    if ($form_state['values']['helion_ksiegarnia_ilosc_ksiazek_na_stronie'] < 10)
        form_set_error('helion_ksiegarnia_ilosc_ksiazek_na_stronie', t('Lista książek nie może być pusta lub mniejsza od 10 pozycji.'));
}

function helion_ksiegarnia_admin_settings_submit() {
        $cache = new cache();
            $cache->clearCacheFiles();
}
