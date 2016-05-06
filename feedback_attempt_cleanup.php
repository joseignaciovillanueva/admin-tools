<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Automated delete attempts from feedback
 *
 * This script executes
 *
 * @package    core
 * @subpackage cli
 * @copyright  2016 Iñaki Villanueva Martinez
 * @email      joseignacio.villanueva@colex.grupo-sm.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions

// now get cli options
list($options, $unrecognized) = cli_get_params(array('help'=>false), array('h'=>'help'));

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) { // TODO localize
    $help = 
"Ejecutar borrado masivo de respuestas en encuestas de tipo feedback.

Este script ejecuta un borrado masivo de las respuestas en encuestas de tipo feedback. Necesita el id de la encuestas tipo feedback del que quieres borrar las respuestas pasado en el prompt.
Opciones:
-h, --help            Imprimir esta ayuda
	
Ejemplo:
\$sudo -u www-data /usr/bin/php /home/user/public_html/admin/cli/feedback_attempt_cleanup.php
";

    echo $help;
    die;
}

$errmsg = 'No ha escrito el id  de la encuestas tipo feedback del que quieres borrar las respuestas '; // TODO localize
cli_heading('Ejecutar borrado masivo de respuestas en encuestas de tipo feedback');  // TODO localize
$prompt = "escribe el id  de la encuestas tipo feedback del que quieres borrar las respuestas)";  // TODO localize
$completedid = cli_input($prompt);
if ($completedid<0) {
   cli_error($errmsg);
   exit(0);
}

$starttime = microtime();
$timenow = time();
mtrace("Server Time: ".date('r',$timenow));
cli_heading("Empieza borrado masivo de respuestas en encuestas de tipo feedback {$completedid}"); // TODO localize
if($DB->delete_records('feedback_valuetmp', array('completed'=>$completedid)))  mtrace("Borrando mdl_feedback_valuetmp completed ".$completedid." "); // TODO localize
if($DB->delete_records('feedback_completedtmp', array('id'=>$completedid))) mtrace("Borrando mdl_feedback_completedtmp id ".$completedid." "); // TODO localize
if($DB->delete_records('feedback_completed', array('feedback'=>$completedid))) 	mtrace("Borrando mdl_feedback_completed feedback ".$completedid." "); // TODO localize
cli_heading('Termina borrado masivo de respuestas en encuestas de tipo feedback'); // TODO localize
$difftime = microtime_diff($starttime, microtime());
mtrace("Server Time: ".date('r',$timenow)."\n\n");
mtrace("Tiempo de ejecución ".$difftime." seconds"); // TODO localize

exit(0);




   
