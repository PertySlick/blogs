<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta charset="utf-8">
  <title>Journey Sync: <?= $title ?></title>
  <meta name="description" content="<?= 'Journey Sync: ' . $desc ?>">
  <meta name="author" content="Timothy Roush">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- BOOTSTRAP STYLES -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
  <link rel="stylesheet" href="/328/dating/styles/styles.css" />
  <!--[if lt IE 9]>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!--
  * Author: Timothy Roush
  * Date Created: 5/12/17
  * Assignment: The Blogs Site
  * Description:  
-->



<!-- MAIN BODY CONTENT -->

<body>
  <h3>Something amazing is gonna happen here...</h3>
  <?php foreach (($bloggers?:[]) as $blogger): ?>
    <div class="tile">
      <?= $blogger[firstName].PHP_EOL ?>
    </div>
  <?php endforeach; ?>

<!-- EXTERNAL JAVASCRIPTS -->  
  
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>