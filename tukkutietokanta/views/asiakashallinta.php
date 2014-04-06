<div class="container">
    <div class="row">
        <h2>Asiakashallinta</h2><br>
        <form class="form-horizontal" action="asiakashallinta.php" method="GET">
            <div class="form-group">
                <label for="asiakasnro" class="col-md-2 control-label">Etsi asiakasnumerolla</label>
                <div class="col-md-2">
                    <input type="text" maxlength="4" class="form-control" id="asiakasnro" name="asiakasnro" placeholder="4 numeroa">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Hae asiakas</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <br>
        <p><a class="btn btn-default" href="asiakashallinta.php?haku=kaikki">Listaa kaikki asiakkaat</a></p>
        <p><a class="btn btn-default" href="asiakashallinta.php?haku=luotto">Listaa luottolimiitin ylittäneet asiakkaat</a></p>
        <p><a class="btn btn-default" href="uusiasiakas.php">Perusta uusi asiakas</a></p>
    </div>
</div>