<div class="container">
    <div class="row">
        <h2><?php $kpl = $data->tuloksia; echo $kpl; ?> hakutulos<?php if ($kpl != 1): {?>ta<?php } endif;?></h2><br>
    </div>

    <?php if ($data->sivuja > 1): {?>
    <div class="row">
        <div class="btn-group">            
            <a <?php if ($data->sivu > 1): ?>
                href="tuotevalikoima.php?haku=listaa&sivu=<?php echo $data->sivu - 1; ?>"
                <?php endif; ?> class="btn btn-default" 
                <?php if ($data->sivu == 1): ?>disabled<?php endif; ?>>
                <span class="glyphicon glyphicon-arrow-left"></span>
            </a>
            <button class="btn btn-success" disabled>
                sivu <?php echo $data->sivu; ?> / <?php echo $data->sivuja; ?>
            </button>
            <a <?php if ($data->sivu < $data->sivuja): ?>
                    href="tuotevalikoima.php?haku=listaa&sivu=<?php echo $data->sivu + 1; ?>"
                <?php endif; ?> class="btn btn-default"
                <?php if ($data->sivu >= $data->sivuja): ?>disabled<?php endif; ?>>
                <span class="glyphicon glyphicon-arrow-right"></span>
            </a>
        </div>
    </div>
    <?php } endif; ?>

    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tuotenumero</th>
                    <th>Valmistajan koodi</th>
                    <th>Kuvaus</th>
                    <th>Valmistaja</th>
                    <th>Varastossa</th>
                    <th>EUR</th>
                    <?php if (onYllapitaja()): { ?>
                            <th>Avoimia tilauksia</th>
                        <?php } endif; ?>
                    <th>&nbsp;</th>
                    <?php if (onYllapitaja()): { ?>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        <?php } endif; ?>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $rivi = $data->rivi;
                foreach ($data->tuotteet as $tuote):?>
                <tr>
                    <td><?php echo $rivi++; ?></td>
                    <td><?php echo $tuote->getTuotenro(); ?></td>
                    <td><?php echo $tuote->getKoodi(); ?></td>
                    <td><?php echo $tuote->getKuvaus(); ?></td>
                    <td><?php echo $tuote->getValmistaja(); ?></td>
                    <td><?php echo $tuote->getSaldo(); ?></td>
                    <td><?php echo $tuote->getHinta(); ?></td>
                    <?php if (onYllapitaja()): { ?>
                        <td><?php echo $tuote->getAvoimiaTilauksia(); ?></td>
                    <?php } endif; ?>
                    <td><a href="tuotevalikoima.php?tuotenro=<?php echo $tuote->getTuotenro(); ?>" 
                        class="btn btn-xs btn-default" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a></td>  
                    <?php if (onYllapitaja()): { ?>
                        <td><a href="tuotevalikoima.php?muokkaa=<?php echo $tuote->getTuotenro(); ?>" 
                            class="btn btn-xs btn-default" target="_blank"><span class="glyphicon glyphicon-wrench"></span></a></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span 
                            class="glyphicon glyphicon-th-list" target="_blank"></span> Avoimet tilaukset</a></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span 
                            class="glyphicon glyphicon-th-list" target="_blank"></span> Kaikki tilaukset</a></td>
                    <?php } else: { ?>
                        <td><a href="#" class="btn btn-xs btn-default"><span 
                            class="glyphicon glyphicon-shopping-cart"></span> </a></td>
                    <?php } endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <p><a class = "btn btn-default" href = "tuotevalikoima.php?haku=uusi">Uusi haku</a></p>
    </div>
</div>