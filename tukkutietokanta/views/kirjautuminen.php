<div class="container">
    <form class="form-horizontal" action="kirjautuminen.php?kirjaudu=sisaan" method="POST">
        <div class="form-group">
            <label for="tunnus" class="col-md-2 control-label">Tunnus</label>
            <div class="col-md-3">
                <input type="text" maxlength="7" class="form-control" id="tunnus" name="tunnus" placeholder="asiakasnumero tai ylläpitäjän tunnus" value="<?php echo $data->tunnus;?>">
            </div>
        </div>
        <div class="form-group">
            <label for="salasana" class="col-md-2 control-label">Salasana</label>
            <div class="col-md-3">
                <input type="password" maxlength="16" class="form-control" id="salasana" name="salasana" placeholder="salasana">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-3">
                <button type="submit" class="btn btn-default">Kirjaudu sisään</button>
            </div>
        </div>
    </form>
</div>