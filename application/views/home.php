<!DOCTYPE html>
<html>
  <head>
    <title>Aurelia</title>
    <link rel="stylesheet" href="<?php echo $aurelia_base; ?>styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body aurelia-app>
    <script src="<?php echo $aurelia_base; ?>jspm_packages/system.js"></script>
    <script src="<?php echo $aurelia_base; ?>config.js"></script>
    <script>
      SystemJS.import('aurelia-bootstrapper');
    </script>
  </body>
</html>

