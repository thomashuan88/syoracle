<!DOCTYPE html>
<html>
  <head>
    <title>Aurelia</title>
    <link rel="stylesheet" href="<?php echo $aurelia_base; ?>jspm_packages/npm/font-awesome@4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $aurelia_base; ?>styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body aurelia-app="main">
    <div class="splash">
      <div class="message">Aurelia Navigation Skeleton</div>
      <i class="fa fa-spinner fa-spin"></i>
    </div>

    <script src="<?php echo $aurelia_base; ?>jspm_packages/system.js"></script>
    <script src="<?php echo $aurelia_base; ?>config.js"></script>
    <script>
      System.import('aurelia-bootstrapper');
    </script>
  </body>
</html>
