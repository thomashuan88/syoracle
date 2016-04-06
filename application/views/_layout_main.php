<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SYOracle Login Form</title>

    <!-- Bootstrap core CSS -->

    <link href="<?php echo $this->include_path; ?>css/bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo $this->include_path; ?>fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $this->include_path; ?>css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="<?php echo $this->include_path; ?>css/icheck/flat/green.css" rel="stylesheet">
    <link rel="<?php echo $this->include_path; ?>stylesheet" type="text/css" href="css/maps/jquery-jvectormap-2.0.3.css" />
    <link href="<?php echo $this->include_path; ?>css/floatexamples.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->include_path; ?>css/custom.css" rel="stylesheet" type="text/css" />
    <?php echo $more_css; ?>

    <script src="<?php echo $this->include_path; ?>js/jquery.min.js"></script>
    <script src="<?php echo $this->include_path; ?>js/nprogress.js"></script>

  <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body <?php echo !empty($body_attr)?$body_attr:''; ?>>
    <?php echo $content_main; ?>

    <script src="<?php echo $this->include_path; ?>js/bootstrap.min.js"></script>
    <?php echo $more_js; ?>
</body>

</html>
