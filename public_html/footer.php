<footer><p>James Stallings and Daniel McNamara For CMSC 508 Spring 2019</p>
<?php function auto_copyright($year = 'auto'){ ?>
   <?php if(intval($year) == 'auto'){ $year = date('Y'); } ?>
   <?php if(intval($year) == date('Y')){ echo intval($year); } ?>
   <?php if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); } ?>
   <?php if(intval($year) > date('Y')){ echo date('Y'); } ?>
<?php } ?>
<?php echo "copyright &copy; "; auto_copyright("2019");
?></footer>
</body>
</html>