<?php

if (!class_exists("helion_ksiegarnia_update")):

    class helion_ksiegarnia_update {

        private $categoryDownloadUrl = "http://%s.pl/plugins/new/xml/lista-katalog.cgi";
        private $booksDownloadUrl = "http://helion.pl/xml/produkty-%s.xml";
    
        private $wydawnictwa = array();
        private $error = array();
        private $xml = NULL;
        private $ignoreTimeToUpdate = FALSE;
        
        private $markiWydawnicze = array(
                "0" => 'helion',
                "1" => 'helion',
                "2" => 'onepress',    
                "3" => 'editio',
                "4" => 'sensus', 
                "5" => 'septem',
                "6" => 'bezdroza',
                "8" => 'podrecznik',
                "11" => 'bezdroża obce',
                "13" => 'ebookpoint',
                "17" => 'videopoint',
        );

        private function _timeToUpdate($wydawnictwo, $update = FALSE) {
            
            $date = db_query("SELECT CURDATE() AS curdate, update_time FROM {helion_ksiegarnia_update} WHERE marka = :marka", array(':marka' => $wydawnictwo))->fetchObject();
            
            $currdate = "0000-00-00";
            $update_time = "0000-00-00";
                    
            if ( !$date ) {
                
                list($currdate, $update_time) = array(date("Y-m-d"), "");
                db_query("INSERT INTO {helion_ksiegarnia_update} VALUES(:marka, :update_time)", array(':marka' => $wydawnictwo, ':update_time'  => "0000-00-00"));
                
            } else if ($date && $update) {
                
                $currdate = $date->curdate;
                $update_time = $date->update_time;
                
                db_query("UPDATE {helion_ksiegarnia_update} SET update_time = :update_time WHERE marka = :marka", array(':update_time' => $date->curdate, ':marka' => $wydawnictwo));
            }
            
            $ret = $currdate > $update_time ? TRUE : FALSE;
            return $ret;
            
        } // private function _timeToUpdate($wydawnictwo)
        
        public function updateCategories($wydawnictwa = "", $ignoreTimeToUpdate = FALSE) {
            
                $this->ignoreTimeToUpdate = $ignoreTimeToUpdate;
            
                if (!isset($wydawnictwa) || empty($wydawnictwa))
                    $this->wydawnictwa = variable_get("wydawnictwa");
                else if (!is_array($wydawnictwa))
                    $this->wydawnictwa = array_filter(preg_split("/(\s|,)/", $wydawnictwa));

                $this->_updateCategories();
            
        } // public function updateCategories($wydawnictwa = null)
        
        public function updateBooks($wydawnictwa = "", $ignoreTimeToUpdate = FALSE) {
            
                $this->ignoreTimeToUpdate = $ignoreTimeToUpdate;
            
                if (!isset($wydawnictwa) || empty($wydawnictwa))
                    $this->wydawnictwa = variable_get("wydawnictwa");
                else if (!is_array($wydawnictwa))
                    $this->wydawnictwa = array_filter(preg_split("/(\s|,)/", $wydawnictwa));

                if ($this->_updateBooks())
                    drupal_goto("ksiegarnia/" . arg(1), array("absolute" => false));
            
        } // public function updateCategories($wydawnictwa = null)


        private function _updateCategories() {

            foreach ($this->wydawnictwa as $wydawnictwo):

                if ($this->_timeToUpdate($wydawnictwo) || $this->ignoreTimeToUpdate) {
                    drupal_set_message(t('Zaktualizowano kategorie ksiegarni :title.', array(':title' => ucfirst($wydawnictwo))));
                
                    $this->xml = $this->_loadContextFromXml($wydawnictwo);

                    db_query("DELETE FROM {helion_ksiegarnia_kategorie} WHERE marka = :wydawnictwo", array(':wydawnictwo' => $wydawnictwo));

                    foreach ($this->xml->item as $kategoria) {
                        $record = new stdClass();
                        $record->title = (string) $kategoria->attributes()->grupa_nad;
                        $record->title_seo = (string) $kategoria->attributes()->seo_nad;
                        $record->title_id = (string) $kategoria->attributes()->id_nad;
                        $record->grupa = (string) $kategoria->attributes()->grupa_pod;
                        $record->grupa_seo = (string) $kategoria->attributes()->seo_pod;
                        $record->grupa_id = (string) $kategoria->attributes()->id_pod;
                        $record->marka = (string) $wydawnictwo;
                        $record->opis = (string) $kategoria;
                        drupal_write_record('helion_ksiegarnia_kategorie', $record);
                    } // foreach($this->xml->item as $kategoria)
                }
                
            endforeach; //foreach ($this->wydawnictwa as $wydawnictwo):
        } //private function _updateCategories()

        private function _updateBooks() {
            
            $ret = FALSE;
            
            foreach ($this->wydawnictwa as $wydawnictwo):
                
                if ($this->_timeToUpdate($wydawnictwo, TRUE) || $this->ignoreTimeToUpdate) {
                    
                    $ret = TRUE;
                    
                    drupal_set_message(t('Zaktualizowano książki z księgarni :title.', array(':title' => ucfirst($wydawnictwo))));
                    
                    $this->xml = $this->_loadContextFromXml($wydawnictwo, "book");

                    if (db_query("DELETE FROM {helion_ksiegarnia} WHERE wydawnictwo = :wydawnictwo",array(':wydawnictwo' =>$wydawnictwo))) {
                        
                        $fields = array('tytul', 'ident', 'wydawnictwo', 
                            'marka', 'autor', 'tlumacz', 'status', 'cena', 
                            'cena_detaliczna', 'znizka', 'liczbastron', 
                            'datawydania', 'okladka', 'issueurl', 'link', 
                            'bestseller', 'nowosc', 'opis', 'typ', 'kategorie', 
                            'promocja', 'oprawa', 'ebook_format'
                        );
                        $values = array();
                        foreach ($this->xml->lista->ksiazka as $ksiazka) {
                            $e_format = "";
                            if ((int) $ksiazka->typ == 2) {
                                $ebook_format = '';
                                foreach ($ksiazka->ebook_formaty->ebook_format as $format)
                                    $ebook_format .= (string) $format . ",";
                                $e_format  = trim($ebook_format,',');
                            }
                            
                            $values[] = array(
                                'tytul'             => (string) $ksiazka->tytul->attributes()->language == "polski" ? (string) $ksiazka->tytul :  (string) $ksiazka->tytul_orginal,
                                'ident'             => strtolower((string) $ksiazka->ident),
                                'wydawnictwo'       => $wydawnictwo,
                                'marka'             => (string) $this->markaWydawnicza( (int) $ksiazka->marka),
                                'autor'             => (string)  $ksiazka->autor,
                                'tlumacz'           => (string)  $ksiazka->tlumacz,
                                'status'            => (int) $ksiazka->status,
                                'cena'              => (float) $ksiazka->cena,
                                'cena_detaliczna'   => (float) $ksiazka->cenadetaliczna,
                                'znizka'            => (int) $ksiazka->znizka,
                                'liczbastron'       => (int) $ksiazka->liczbastron,
                                'datawydania'       => (string) $ksiazka->datawydania,
                                'okladka'           => (string) $ksiazka->okladka,
                                'issueurl'          => (string) $ksiazka->issueurl,
                                'link'              => (string) $ksiazka->link,
                                'bestseller'        => (int) $ksiazka->bestseller,
                                'nowosc'            => (int) $ksiazka->nowosc,
                                'opis'              => (string) $ksiazka->opis,
                                'typ'               => (int) $ksiazka->typ,
                                'kategorie'         => "," . join(",", $this->kategorie_id($ksiazka)) . ",",
                                'promocja'          => isset ($ksiazka->promocja) ? $ksiazka->promocja->attributes()->id : 0,
                                'oprawa'            => (string) $ksiazka->oprawa,
                                'ebook_format'      => $e_format,
                            );
                            
                            /*
                             *  too many queries
                                $record = new stdClass();
                                $record->tytul =  (string) $ksiazka->tytul->attributes()->language == "polski" ? (string) $ksiazka->tytul :  (string) $ksiazka->tytul_orginal;
                                $record->ident  = strtolower((string) $ksiazka->ident);                
                                $record->wydawnictwo = $wydawnictwo;
                                $record->marka = (string) $this->markaWydawnicza( (int) $ksiazka->marka);
                                $record->autor = (string)  $ksiazka->autor;
                                $record->tlumacz = (string)  $ksiazka->tlumacz;
                                $record->status = (int) $ksiazka->status;
                                $record->cena = (float) $ksiazka->cena;
                                $record->cena_detaliczna = (float)   $ksiazka->cenadetaliczna;
                                $record->znizka = (int) $ksiazka->znizka;
                                $record->liczbastron = (int)     $ksiazka->liczbastron;
                                $record->datawydania  = (string)  $ksiazka->datawydania;
                                $record->okladka = (string)  $ksiazka->okladka;
                                $record->issueurl = (string)  $ksiazka->issueurl;
                                $record->link = (string)  $ksiazka->link;
                                $record->bestseller = (int)  $ksiazka->bestseller;
                                $record->nowosc = (int) $ksiazka->nowosc;
                                $record->opis = (string) $ksiazka->opis;
                                $record->typ = (int) $ksiazka->typ;
                                $record->kategorie = "," . join(",", $this->kategorie_id($ksiazka)) . ",";
                                $record->promocja = isset ($ksiazka->promocja) ? $ksiazka->promocja->attributes()->id : 0;
                                $record->oprawa = (string)  $ksiazka->oprawa;
                                if ($record->typ == 2) {
                                    $ebook_format = '';
                                    foreach ($ksiazka->ebook_formaty->ebook_format as $format)
                                        $ebook_format .= (string) $format . ",";
                                    $record->ebook_format   =   trim($ebook_format,',');
                                }    
                                drupal_write_record('helion_ksiegarnia', $record);
                            */  
                        }
                        
                        if (is_array($values) && count($values) > 0) {
                            $query = db_insert('helion_ksiegarnia')->fields($fields);
                            foreach ($values as $rec) {
                                $query->values($rec);
                            }
                            $query->execute();
                        }
                        
                    }
                }
                
            endforeach;
            
            return $ret;
            
        } // private function _updateBooks()
        
        private function markaWydawnicza($id_marki) {
                return isset($this->markiWydawnicze[$id_marki]) ? $this->markiWydawnicze[$id_marki] : $this->markiWydawnicze[0];
        } // private function markaWydawnicza($id_marki)
        
        private function kategorie_id($ksiazka) {
            
            $kategorie = array();
            
            if ($ksiazka->serietematyczne)
                foreach ($ksiazka->serietematyczne->seriatematyczna as $kategoria)
                    array_push($kategorie, $kategoria->attributes()->id);
            
            return $kategorie;
            
        } // private function kategorie_id($ksiazka) 
        
        
        private function _loadContextFromXml($wydawnictwo, $categoryOrBooks = "category") {
            
            $downloadUrl = sprintf($categoryOrBooks == "category" ? $this->categoryDownloadUrl : $this->booksDownloadUrl, $wydawnictwo);
            
            $context = file_get_contents($downloadUrl);
            
            return $context ? simplexml_load_string($context, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE) : array_push($this->error, "Bląd pobrania kategorii z adresu {$downloadUrl}.");
            
        } // private function _loadContextFromXml($wydawnictwo)

    }
    
endif;