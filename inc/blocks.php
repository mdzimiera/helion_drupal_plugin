<?php
    function helion_ksiegarnia_block_info() {
        $blocks['helion_ksiegarnia_losowa_ksiazka'] = array(
            'info'      =>  t('Helion Księgarnia - polecana książka'),
            'status'    =>  FALSE,
            'weight'    =>  0,
            'visibility'=>  2,
        );
    
        $blocks['helion_ksiegarnia_bestseller'] = array(
            'info'      =>  t('Helion Księgarnia - bestseller'),
            'status'    =>  FALSE,
            'weight'    =>  0,
            'visibility'=>  2,
        );
    
        $blocks['helion_ksiegarnia_promocja_dnia'] = array(
            'info'      =>  t('Helion Księgarnia - promocja dnia'),
            'status'    =>  FALSE,
            'weight'    =>  0,
            'visibility'=>  2,
        );
        return $blocks;
    }
    
    function helion_ksiegarnia_block_view($delta='') {
        $wydawnictwo    = variable_get('helion_ksiegarnia_losowa_ksiazka_kategoria', 'helion');
        $title          = "";
        $additional_sql = "";
    
        switch ($delta) {
            case    'helion_ksiegarnia_losowa_ksiazka': {
                $title  = t("Polecana książka");
            } break;
            case    'helion_ksiegarnia_bestseller': {
                $title          = t("Bestseller");
                $additional_sql = " AND bestseller = 1 ";
                $wydawnictwo    = variable_get("{$delta}_kategoria", "helion");
            } break;    
            case    'helion_ksiegarnia_promocja_dnia': {
                $title          = t("Promocja dnia");
                $additional_sql = " AND promocja = 1 ";
                $wydawnictwo    = variable_get("{$delta}_kategoria", "helion");            
            } break;        
        }
    
        doForceUpdate($wydawnictwo);
    
        $block['subject']   = $title;
        $random_sql         = db_driver()=='pgsql' ? "RANDOM()" : "RAND()";
        $where_sql          = " WHERE marka != {$random_sql}";
    
        if ($wydawnictwo!="wszystkie") {
            if (!preg_match('/[0-9]+$/', $wydawnictwo)) 
                $where_sql = " WHERE marka = '{$wydawnictwo}' ";
            else {
                $ids = db_query("SELECT title_id,grupa_id FROM {helion_ksiegarnia_kategorie} WHERE marka = :marka AND title_id = :id", array(
                            ":marka"    => preg_replace('/[0-9-]/', '', $wydawnictwo),
                            ":id"       => preg_replace('/[^0-9]/', '', $wydawnictwo)
                ));
            $id = ' AND (';
            foreach ($ids as $i)
                $id .= "kategorie LIKE '%," . ($i->grupa_id ? $i->grupa_id : $i->title_id) . ",%' OR ";
            $id = preg_replace("/OR\s+$/", "", $id) . ")";
            $id = preg_replace("/AND (kategorie LIKE '%%')/", "", $id);
            $where_sql = " WHERE wydawnictwo = '" . preg_replace('/[0-9-]/', '', $wydawnictwo) . "' " . $id;
        }
        
    } //if($wydawnictwo!="wszystkie")
    
    $where_ebooks_sql = variable_get("helion_ksiegarnia_ebooki")=='1' ? " AND typ = 2 " : "";
        $sql = sprintf("SELECT okladka,tytul,cena,marka,ident FROM {helion_ksiegarnia} %s %s %s ORDER BY %s LIMIT 1",
                $where_sql,
                $where_ebooks_sql,
                $additional_sql,
                $random_sql
        );
    $book = db_query($sql)->fetch();
    
    if($book) {
        $block['content'] = theme("helion_ksiegarnia_opis_ksiazki",array(
            "data"              => $book,
            "ident"             => variable_get('helion_ksiegarnia_helion_user_id')."/".strtoupper($book->ident),
            "img_src_koszyk"    => variable_get('helion_ksiegarnia_koszyk','http://helion.pl/img/koszyk/koszszary.jpg'),
            "opcje"             => variable_get("helion_ksiegarnia_losowa_ksiazka_ustawienia",array(
                                        'tytul' => 1,
                                        'cena'  => 1,
                                        'koszyk'=> 1)
            ),
            "url"               => url(arg(0).'/'.$book->marka.'/ident-'.$book->ident,array('absolute'=>FALSE))
        ));
        return $block;
    } // if($book)
} // function helion_ksiegarnia_block_view($delta='')