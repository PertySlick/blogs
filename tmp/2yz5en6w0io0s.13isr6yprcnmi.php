<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <img src="images/notepad.png" alt="Notepad" id="notepad" />
  <h1>Become a Blogger!</h1>
  <h3>Create a new account below</h3>
</section>
<section>
  <form action="./register" method="POST" class="form-horizontal" enctype="multipart/form-data" name="info">
    <div class="row">
      <div class="col-sm-6 no-pad-right">
        <div class="input-wrapper">
          <div class="form-group">
            <div class="input-group">
              <input type="text" class="form-control" name="userName" id="userName" value="<?= $nonsense ?>" required="required" />
              <span class="input-group-addon"><label for="username">Username</label></span>
            </div>
            <div class="input-group sep-field">
              <input type="text" class="form-control" name="email" id="email" placeholder="Email" required="required" />
              <span class="input-group-addon"><label for="email">Email</label></span>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required="required" />
              <span class="input-group-addon"><label for="password">Password</label></span>
            </div>
            <div class="input-group sep-field">
              <input type="password" class="form-control" name="verify" id="verify" placeholder="Verify Password" required="required" />
              <span class="input-group-addon"><label for="verify">Verify</label></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 no-pad-left">
        <div class="input-wrapper">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="lame" id="lame" class="form-control" readonly />
              <label class="input-group-btn">
                <span class="btn btn-info" name="temp" id="temp">
                  Upload Portrait<input type="file" name="image" id="image" />
                </span>
              </label>
            </div>
            <div class="shadelabel toplabel sep-field"><label for="bio">Quick Biography</label></div>
            <textarea class="form-control labeltop" rows="5" name="bio" id="bio" required="required" ></textarea>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" name="action" class="btn btn-info btn-lg center-block" value="create">Start Blogging!</button>
  </form>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>