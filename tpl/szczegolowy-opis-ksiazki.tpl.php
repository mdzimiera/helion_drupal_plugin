<?php $book = $data;?>
Data wydania: <?= $book->datawydania ?>

<br />

Liczba stron:<?= $book->liczbastron ?>
<br />

Oprawa: <?= $book->oprawa ?>

<br />
<br />
<?php if ($book->issueurl): ?>

    Fragmenty książki: <a href="<?= $book->issueurl ?>" target="_blank"><?= $book->issueurl ?></a>
    
    <br />
    
<?php endif; ?>
    
<br />

<?php if ($book->ebook_format != ''): ?>

    Format eBook:
    
    <?php foreach (preg_split("/,/", $book->ebook_format) as $format): ?>
    
        <img src="<?= $ebook_format_icon[$format] ?>" alt="<?= $book->ebook_format ?>" />&nbsp;<?= $format ?>&nbsp;
        
    <?php endforeach; ?>

<?php endif; ?>    

<?php if ($book->nowosc): ?>

    <img src="<?= variable_get('helion_ksiegarnia_nowosc', HK_PATH . 'gfx/nowosc.gif') ?>" alt="<?= $book->tytul ?>" />&nbsp;

<?php endif; ?>

<?php if ($book->bestseller): ?>

    <img src="<?= variable_get('helion_ksiegarnia_bestseller', HK_PATH . 'gfx/bestseller.gif') ?>" alt="<?= $book->tytul ?>" />&nbsp;

<?php endif; ?>

