<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <img src="images/notepad.png" alt="Notepad" id="notepad" />
  <h1>Become a Blogger!</h1>
  <h3>Create a new account below</h3>
</section>
<section>
  <form action="./register" method="POST" class="form-horizontal"
        enctype="multipart/form-data" name="registration" id="registration">
    <div class="row">
      <div class="col-sm-6 no-pad-right">
        <div class="input-wrapper">
          <div class="form-group">
            <div class="input-group <?= $userNameError?'has-error':'' ?>">
              <input type="text" class="form-control" name="userName" 
                     id="userName" placeholder="User Name" 
                     value="<?= $userName ?>" data-required="true" />
              <span class="input-group-addon"><label for="username">Username</label></span>
              <div class="help-block"><?= $userNameError ?></div>
            </div>
            <div class="input-group sep-field <?= $emailError?'has-error':'' ?>">
              <input type="text" class="form-control" name="email" 
                     id="email" placeholder="Email"
                     value="<?= $email ?>" data-required="true" />
              <span class="input-group-addon"><label for="email">Email</label></span>
              <div class="help-block"><?= $emailError ?></div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group <?= $passwordError?'has-error':'' ?>">
              <input type="password" class="form-control" name="password" 
                     id="password" placeholder="Password" data-required="true" />
              <span class="input-group-addon"><label for="password">Password</label></span>
              <div class="help-block"><?= $passwordError ?></div>
            </div>
            <div class="input-group sep-field <?= $verifyError?'has-error':'' ?>">
              <input type="password" class="form-control" name="verify" 
                     id="verify" placeholder="Verify Password" data-required="true" />
              <span class="input-group-addon"><label for="verify">Verify</label></span>
              <div class="help-block"><?= $verifyError ?></div>
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
              <div class="help-block"></div>
            </div>
            <div class="input-group <?= $bioError?'has-error':'' ?>">
              <div class="shadelabel toplabel sep-field">
                <label for="bio">Quick Biography</label>
              </div>
              <textarea class="form-control labeltop" rows="5" name="bio" id="bio"
                        placeholder="A short description of yourself..."
                        data-required="true"><?= $bio ?></textarea>
              <div class="help-block"><?= $bioError ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" name="action" class="btn btn-info btn-lg center-block" value="create">Start Blogging!</button>
  </form>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>