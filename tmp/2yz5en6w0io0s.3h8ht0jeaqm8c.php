<!DOCTYPE html>
<html>
<head>
  <title>Hello World!</title>
</head>
<body>
  <h3>Something amazing is gonna happen here...</h3>
  <?php foreach (($bloggers?:[]) as $blogger): ?>
    <div class="tile">
      <?= $blogger[firstName].PHP_EOL ?>
    </div>
  <?php endforeach; ?>
</body>
</html>