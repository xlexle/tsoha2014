<div class="container">
    <h1>Tuomiopäivän Tukkuliike</h1>
    <p><em>Kaikkea mahdollista pahan päivän varalle</em></p><br><br>
    <form class="form-horizontal" action="kirjaudu.php" method="POST">
        <div class="form-group">
            <label for="username" class="col-md-2 control-label">Tunnus</label>
            <div class="col-md-5">
                <input type="text" class="form-control" id="username" name="username" placeholder="asiakasnumero tai ylläpitäjän tunnus" value="<?php echo $data->tunnus; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-md-2 control-label">Salasana</label>
            <div class="col-md-5">
                <input type="password" class="form-control" id="password" name="password" placeholder="salasana">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-5">
                <div class="checkbox">
                    <label><input type="checkbox"> Muista kirjautumistiedot</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-5">
                <button type="submit" class="btn btn-default">Kirjaudu sisään</button>
            </div>
        </div>
    </form>
</div>