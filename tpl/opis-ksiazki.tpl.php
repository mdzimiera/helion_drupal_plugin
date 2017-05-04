<div class="hk_book_single">
    <a href="<?=$url?>">
        <img src="<?=$data->okladka?>" alt="<?=$data->okladka?>" style="border:0px; width:100%;" />
    </a>
    <?php if($opcje['tytul']):?>
    <br />
    <br />
    <a href="<?=$url?>" class="hk_tytul">
        <strong><?=$data->tytul?></strong>
    </a>
    <?php endif;?>
    <?php if($opcje['cena']):?>
    <br />
    <br />
    <span class="hk_cena">Cena: <?=$data->cena?> zł</span>
    <?php endif;?>
    <?php if($opcje['koszyk']):?>                
    <br />
    <br />
    <a href="http://<?=$data->marka?>.pl/add/<?=$ident?>" class="hk_basket" target="_blank">
        <img src="<?=$img_src_koszyk?>" alt="<?=$data->tytul?>" />
    </a>
    <?php endif;?>
</div>                