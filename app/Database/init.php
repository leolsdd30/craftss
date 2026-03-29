<?php
// This file exists for CLI scripts (e.g. Migrations.php) that are run
// outside the normal bootstrap flow and need the Database class available.
// We simply load the real Database.php — no duplicate class definition.
require_once __DIR__ . '/Database.php';