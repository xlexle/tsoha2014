<div class="container">
    <div class="row">
        <h1>Tuomiopäivän Tukkuliike</h1>
        <p><em>Kaikkea mahdollista pahan päivän varalle.</em></p><br>
    </div>

    <?php if (kirjautunut()): { ?>
            <div class="row">

                <!-- PÄIVITTYVÄ TABINAVIGAATIO -->
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <!-- TAB 1 -->
                        <li <?php if ($tab==1): { ?>
                                    class="active"
                                <?php } endif; ?>>
                            <a href="tuotevalikoima.php">Tuotevalikoima</a></li>

                        <!-- TAB 2 -->
                        <?php if (!onYllapitaja()): { ?>
                                <li <?php if ($tab==2): { ?>
                                            class="active"
                                        <?php } endif; ?>>
                                    <a href="ostoskori.php">Ostoskori</a></li>
                            <?php } endif; ?>

                        <!-- TAB 3 -->
                        <li <?php if ($tab==3): { ?>
                                    class="active"
                                <?php } endif; ?>>
                            <a href="tilausseuranta.php">Tilausseuranta</a></li>

                        <!-- TAB 4 -->
                        <?php if (onYllapitaja()): { ?>
                                <li <?php if ($tab==4): { ?>
                                            class="active"
                                        <?php } endif; ?>>
                                    <a href="asiakashallinta.php">Asiakashallinta</a></li>
                            <?php } endif; ?>
                    </ul>
                </div>

                <!-- ULOSKIRJAUTUMINEN-PAINIKE -->
                <div class="col-md-2">
                    <a class="btn btn-default" href="kirjautuminen.php?kirjaudu=ulos">Kirjaudu ulos</a>
                </div>
            </div>
        <?php } endif; ?>
</div>
