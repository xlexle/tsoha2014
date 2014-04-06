<div class="container">
    <div class="row">
        <h2>Tilausseuranta</h2><br>
        <form class="form-horizontal" action="tilausseuranta.php?haku=listaa" method="POST">
            <?php if (onYllapitaja()): { ?>
                    <div class="form-group">
                        <label for="asiakasnro" class="col-md-2 control-label">Asiakasnumero</label>
                        <div class="col-md-2">
                            <input type="text" maxlength="4" class="form-control" id="asiakasnro" name="asiakasnro" placeholder="4 numeroa">
                        </div>
                    </div>
                <?php } endif; ?>
            <div class="form-group">
                <label for="viite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="viite" name="viite">
                </div>
            </div>
            <div class="form-group">
                <label for="tuotenro" class="col-md-2 control-label">Tuotenumero</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="tuotenro" name="tuotenro" placeholder="6 numeroa">
                </div>
            </div>
            <div class="form-group">
                <label for="luontipvm_min" class="col-md-2 control-label">Luotu</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="luontipvm_min" name="luontipvm_min">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="luontipvm_max" name="luontipvm_max">
                </div>
            </div>
            <div class="form-group">
                <label for="toimituspvm_min" class="col-md-2 control-label">Toimitettu</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="toimituspvm_min" name="toimituspvm_min">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="toimituspvm_max" name="toimituspvm_max">
                </div>
            </div>
            <div class="form-group">
                <label for="laskutuspvm_min" class="col-md-2 control-label">Laskutettu</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="laskutuspvm_min" name="laskutuspvm_min">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="laskutuspvm_max" name="laskutuspvm_max">
                </div>
            </div>
            <div class="form-group">
                <label for="maksupvm_min" class="col-md-2 control-label">Maksettu</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="maksupvm_min" name="maksupvm_min">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="maksupvm_max" name="maksupvm_max">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Hae tilauksia</button>
                </div>
            </div>
        </form>

        <br>
        <form class="form-horizontal" action="tilausseuranta.php" method="GET">
            <div class="form-group">
                <label for="tilausnro" class="col-md-2 control-label">Etsi tilausnumerolla</label>
                <div class="col-md-2">
                    <input type="text" maxlength="8" class="form-control" id="tilausnro" name="tilausnro" placeholder="8 numeroa">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Hae tilaus</button>
                </div>
            </div>
        </form>  
    </div>
</div>