<!DOCTYPE html>
<html>
  <head>
    <title>SYOracle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
    var syoracle_info = <?php echo !empty($appinfo)?json_encode($appinfo):'{}'; ?>;
    </script>
  </head>

  <body aurelia-app>
    <script src="<?php echo $aurelia_base; ?>jspm_packages/system.js"></script>
    <script src="<?php echo $aurelia_base; ?>config.js"></script>
    <script>
      SystemJS.import('aurelia-bootstrapper');
    </script>
  </body>
</html>

