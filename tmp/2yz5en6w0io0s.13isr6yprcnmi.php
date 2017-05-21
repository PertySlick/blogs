<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <title>Blogs: <?= $title ?></title>
  <meta name="description" content="<?= 'Blogs: ' . $desc ?>" />
  <meta name="author" content="Timothy Roush" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- BOOTSTRAP STYLES -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
  <link rel="stylesheet" href="/328/blogs/css/style.css" />
  <!--[if lt IE 9]>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-3 sidenav">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12">
            <h1 class="text-center">Blog Site</h1>
            <img src="images/trumpet.gif" id="trumpet" alt="Brand Image" />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <a href="<?= $BASE ?>">Home ></a><br />
            <?php if ($user): ?>
              
                <?= $current->getID().PHP_EOL ?>
                <a href="<?= '/myblogs' . $current->getID() ?>">My Blogs ></a><br />
                <a href="/create">Create Blog ></a><br />
              
              <?php else: ?>
                <a href="/register">Become A Blogger</a><br />
              
            <?php endif; ?>
            <a href="/about">About Us ></a><br />
            <?php if ($user): ?>
              
                <a href="/logout">Logout</a><br />
              
              <?php else: ?>
                <a href="/login">Login</a>
              
            <?php endif; ?>
            
            
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9" style="background-color:#bbb;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12">
            Header
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            Content
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <form action="#" method="POST" class="form-horizontal" enctype="multipart/form-data" name="info">
      <div class="col-sm-6">
        <div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" />
            <span class="input-group-addon"><label for="username">Username</label></span>
          </div>
          <div class="input-group">
            <input type="text" class="form-control" name="email" id="email" placeholder="Email" />
            <span class="input-group-addon"><label for="email">Email</label></span>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
            <span class="input-group-addon"><label for="password">Password</label></span>
          </div>
          <div class="input-group">
            <input type="password" class="form-control" name="verify" id="verify" placeholder="Verify Password" />
            <span class="input-group-addon"><label for="verify">Verify</label></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="lame" id="lame" class="form-control" readonly />
            <label class="input-group-btn">
              <span class="btn btn-info" name="temp" id="temp">
                Upload Portrait<input type="file" name="image" id="image" />
              </span>
            </label>
          </div>
        </div>
        <div class="form-group">
          <div class="shadelabel"><label for="bio">Quick Biography</label></div>
          <textarea class="form-control" rows="5" name="bio" id="bio"></textarea>
        </div>
      </div>
      <button type="submit" name="action" class="btn btn-info" value="create">Submit</button>
    </form>
  </div>
</div>
  
  
  
  
  
  
  
  
  
  
  
  
  <!-- EXTERNAL JAVASCRIPTS -->  
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/blogs.js"></script>
</body>
</html>