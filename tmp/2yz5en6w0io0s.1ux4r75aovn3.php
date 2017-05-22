<!--
  * Author: Timothy Roush
  * Date Created: 5/13/17
  * Assignment: The Blogs Site
  * Description:  Blog Login Page
-->

<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <div class="pull-right blog-emblem">BLOG</div>
  <h1>Welcome back!</h1>
  <h3>Please log in below</h3>
</section>
<section class="frame">
  <form action="./login" method="POST" class="form-horizontal">
    <div class="float-panel">
      <div class="input-group">
        <input type="text" class="form-control" name="userName" id="userName" value="<?= $userName ?>" />
        <span class="input-group-addon">Username</span>
      </div>
      <div class="input-group sep-field">
        <input type="password" class="form-control" name="password" id="password" />
        <span class="input-group-addon">Password</span>
      </div>
    </div>
    <button class="btn btn-success btn-lg center-block sep-field" type="submit" name="action" id="action" value="login">Log in</button>
  </form>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>