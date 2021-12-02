<?php defined('COREPATH') or exit('No direct script access allowed'); ?>

ERROR - 2021-05-18 11:48:24 --> 42S02 - SQLSTATE[42S02]: Base table or view not found: 1146 Table 'archeohandi.sujet_handicap' doesn't exist with query: "SELECT id_groupe_sujets FROM sujet_handicap WHERE id_operation=1" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 11:49:07 --> 42S02 - SQLSTATE[42S02]: Base table or view not found: 1146 Table 'archeohandi.groupe_sujet' doesn't exist with query: "SELECT id_groupe_sujets FROM groupe_sujet WHERE id_operation=1" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 11:49:23 --> Notice - Array to string conversion in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 105
ERROR - 2021-05-18 11:50:31 --> 42S02 - SQLSTATE[42S02]: Base table or view not found: 1146 Table 'archeohandi.sujet_handicap' doesn't exist with query: "SELECT * FROM sujet_handicap WHERE id_groupe_sujets=1" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 11:53:40 --> Warning - Illegal string offset 'id_type_depot' in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 129
ERROR - 2021-05-18 11:58:10 --> Notice - Object of class Fuel\Core\Database_PDO_Cached could not be converted to number in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 106
ERROR - 2021-05-18 11:59:03 --> Notice - Array to string conversion in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 104
ERROR - 2021-05-18 12:00:49 --> Error - Database results are read-only in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/cached.php on line 198
ERROR - 2021-05-18 12:02:36 --> Error - Database results are read-only in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/cached.php on line 198
ERROR - 2021-05-18 12:04:02 --> Error - Call to undefined function as_array() in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 107
ERROR - 2021-05-18 12:05:39 --> Warning - asort() expects parameter 1 to be array, object given in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 129
ERROR - 2021-05-18 12:06:59 --> Warning - Invalid argument supplied for foreach() in /sites/archeohandi/www/web_main/fuel/app/views/operations/view.php on line 130
ERROR - 2021-05-18 15:10:33 --> Error - syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) in /sites/archeohandi/www/web_main/fuel/app/classes/controller/operations.php on line 210
ERROR - 2021-05-18 15:11:23 --> Notice - Undefined variable: type in /sites/archeohandi/www/web_main/fuel/app/classes/controller/operations.php on line 142
ERROR - 2021-05-18 15:12:14 --> Notice - Undefined variable: content in /sites/archeohandi/www/web_main/fuel/app/views/template.php on line 45
ERROR - 2021-05-18 15:13:28 --> Notice - Undefined variable: type in /sites/archeohandi/www/web_main/fuel/app/views/operations/edit.php on line 1
ERROR - 2021-05-18 15:13:43 --> Notice - Undefined variable: type in /sites/archeohandi/www/web_main/fuel/app/views/operations/edit.php on line 1
ERROR - 2021-05-18 15:13:44 --> Notice - Undefined variable: type in /sites/archeohandi/www/web_main/fuel/app/views/operations/edit.php on line 1
ERROR - 2021-05-18 15:14:12 --> Error - syntax error, unexpected 'endif' (T_ENDIF), expecting end of file in /sites/archeohandi/www/web_main/fuel/app/views/operations/edit.php on line 193
ERROR - 2021-05-18 15:15:10 --> Notice - Undefined variable: id in /sites/archeohandi/www/web_main/fuel/app/classes/controller/sujet.php on line 6
ERROR - 2021-05-18 15:15:36 --> Notice - Undefined variable: type in /sites/archeohandi/www/web_main/fuel/app/classes/controller/sujet.php on line 20
ERROR - 2021-05-18 15:16:27 --> Error - syntax error, unexpected 'endif' (T_ENDIF), expecting end of file in /sites/archeohandi/www/web_main/fuel/app/views/sujet/edit.php on line 227
ERROR - 2021-05-18 15:56:27 --> Error - syntax error, unexpected end of file in /sites/archeohandi/www/web_main/fuel/app/views/sujet/view.php on line 148
ERROR - 2021-05-18 15:57:28 --> Error - syntax error, unexpected '}', expecting end of file in /sites/archeohandi/www/web_main/fuel/app/classes/controller/sujet.php on line 148
ERROR - 2021-05-18 15:57:58 --> Error - syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) in /sites/archeohandi/www/web_main/fuel/app/classes/controller/sujet.php on line 149
ERROR - 2021-05-18 15:58:00 --> Error - syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) in /sites/archeohandi/www/web_main/fuel/app/classes/controller/sujet.php on line 149
ERROR - 2021-05-18 15:58:15 --> Error - syntax error, unexpected end of file in /sites/archeohandi/www/web_main/fuel/app/views/sujet/view.php on line 148
ERROR - 2021-05-18 15:59:05 --> 42000 - SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 1 with query: "SELECT * FROM depot WHERE id_depot=" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 16:00:29 --> 42S22 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'id_depot' in 'where clause' with query: "SELECT * FROM depot WHERE id_depot=""" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 16:02:28 --> 42S02 - SQLSTATE[42S02]: Base table or view not found: 1146 Table 'archeohandi.groupe_sujet' doesn't exist with query: "SELECT * FROM groupe_sujet WHERE id_groupe_sujets=1" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 16:02:52 --> Notice - Undefined index: NMI in /sites/archeohandi/www/web_main/fuel/app/views/sujet/view.php on line 118
ERROR - 2021-05-18 16:03:50 --> Notice - Array to string conversion in /sites/archeohandi/www/web_main/fuel/app/views/sujet/view.php on line 123
ERROR - 2021-05-18 17:15:04 --> 42S22 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'operation' in 'where clause' with query: "SELECT * FROM operations WHERE id_site=operation" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 17:15:14 --> 42S22 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'operation' in 'where clause' with query: "SELECT * FROM operations WHERE id_site=operation" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 17:17:35 --> 42S22 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'operation' in 'where clause' with query: "SELECT * FROM operations WHERE id_site=operation" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
ERROR - 2021-05-18 17:19:02 --> 42S22 - SQLSTATE[42S22]: Column not found: 1054 Unknown column 'operation' in 'where clause' with query: "SELECT * FROM operations WHERE id_site=operation" in /sites/archeohandi/www/web_main/fuel/core/classes/database/pdo/connection.php on line 235
