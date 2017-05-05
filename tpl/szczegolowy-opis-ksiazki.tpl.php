<?php $book = $data;?>
Data wydania: <b><?php echo $book->datawydania ?></b>

<?php if ($book->liczbastron):?>
<br />

Liczba stron: <b><?php echo $book->liczbastron ?></b>
<?php endif;?>

<?php if ($book->oprawa):?>
<br />
Oprawa: <b><?php echo $book->oprawa ?></b>
<?php endif;?>

<?php if ($book->issueurl): ?>
    <br />
    Fragmenty książki: <b><a href="<?php echo $book->issueurl ?>" target="_blank"><?php echo $book->issueurl ?></a></b>
    
<?php endif; ?>
    
<br />
Typ: <b><?php echo getTypeByIdent($book->ident)?></b>
<?php if ($book->ebook_format != ''): ?>
    <br />
    Format eBook: <b><?php foreach (preg_split("/,/", $book->ebook_format) as $format): ?><?php echo $format ?>&nbsp;<?php endforeach; ?>
    </b>
<?php endif; ?>    
<br /><br />
<ul class="hk_tags">
<?php if ($book->nowosc): ?>

    <li class="tag-new">Nowość</li>    
    <?php /*<img src="<?php echo variable_get('helion_ksiegarnia_nowosc', HK_PATH . 'gfx/nowosc.gif') ?>" alt="<?php echo $book->tytul ?>" />&nbsp;*/?>

<?php endif; ?>

<?php if ($book->bestseller): ?>

    <li class="tag-bestseller">Bestseller</li>
    <?php /*<img src="<?php echo variable_get('helion_ksiegarnia_bestseller', HK_PATH . 'gfx/bestseller.gif') ?>" alt="<?php echo $book->tytul ?>" />&nbsp;*/?>

<?php endif; ?>
</ul>

