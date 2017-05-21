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
    <div class="col-sm-2 sidenav">
      <section class="frame">
        <h2 class="text-center">Blog Site</h2>
        <img src="images/trumpet.gif" id="trumpet" alt="Brand Image" />
        <nav>
          <a href="<?= $BASE ?>">Home ></a><br />
          <?php if ($user === true): ?>
            
              <a href="<?= '/myblogs/' . $current->getID() ?>">My Blogs ></a><br />
              <a href="/create">Create Blog ></a><br />
            
            <?php else: ?>
              <a href="register">Become A Blogger</a><br />
            
          <?php endif; ?>
          <a href="about">About Us ></a><br />
          <?php if ($user): ?>
            
              <a href="logout">Logout ></a><br />
            
            <?php else: ?>
              <a href="login">Login ></a>
            
          <?php endif; ?>
        </nav>
      </section>
    </div>
    <div class="col-sm-10">