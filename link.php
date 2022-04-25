<?php
declare(strict_types=1);
?>
<html>
<head>
   <?php require('head.php'); ?>
</head>
<body>

<?php
if (isset($_GET['type'], $_GET['id'])) {
   $type = $_GET['type'];
   $id   = $_GET['id'];
   if ($type === 'system') {
      $url = '/systems.php?id=' . $id;
      header('Location: ' . $url);
      echo '<h1>Redirecting...</h1><p>If the page does not redirect automatically, <a href="' . $url . '">click here</a>';
      echo '<meta http-equiv="refresh" content="0; URL=' . $url . "\">";
   } else {
      echo 'Invalid type!';
   }
} else {
   echo '<h1>Invalid link specified!</h1>';
}
echo '<hr>';
require('footer.php');
?>