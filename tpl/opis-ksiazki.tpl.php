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
    <div class="hk_cena">Cena: <?=$data->cena?> zł</div>
    <?php endif;?>
    <?php if($opcje['koszyk']):?>                
    <br />
    <br />
    <div class='hk_helion_box hk_details'>
        <a href="http://<?php echo $data->marka?>.pl/add/<?php echo $ident?>" target="_blank" title='Kup teraz'>Kup teraz</a>
    </div> 
    <?php /*<a href="http:<?=$data->marka?>.pl/add/<?=$ident?>" class="hk_basket" target="_blank">
        <img src="<?=$img_src_koszyk?>" alt="<?=$data->tytul?>" />
    </a>*/?>
    <?php endif;?>
</div>                