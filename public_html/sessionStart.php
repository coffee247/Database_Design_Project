<?php
  // Start a session and regenerate ID to prevent session hijacking.
  session_start();
  @session_regenerate_id(true);
  $_SESSION['sessionid'] = session_id();
?>