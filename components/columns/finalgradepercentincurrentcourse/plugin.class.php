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
 * Configurable Reports
 * A Moodle block for creating customizable reports
 * @package blocks
 * @author: Juan leyva <http://www.twitter.com/jleyvadelgado>
 * @date: 2009
 */

require_once($CFG->dirroot.'/blocks/configurable_reports/plugin.class.php');

class plugin_finalgradepercentincurrentcourse extends plugin_base {

    public function init() {
        $this->fullname = get_string('finalgradepercentincurrentcourse', 'block_configurable_reports');
        $this->form = true;
        $this->reporttypes = array('users');
    }

    public function summary($data) {
        return format_string($data->columname);
    }

    public function colformat($data) {
        $align = (isset($data->align)) ? $data->align : '';
        $size = (isset($data->size)) ? $data->size : '';
        $wrap = (isset($data->wrap)) ? $data->wrap : '';
        return array($align, $size, $wrap);
    }

    // Data -> Plugin configuration data
    // Row -> Complet course row c->id, c->fullname, etc...
    public function execute($data, $row, $user, $courseid, $starttime = 0, $endtime = 0) {
        global $DB, $USER, $CFG;

        $userid = $row->id;
        require_once($CFG->libdir.'/gradelib.php');
        require_once($CFG->dirroot.'/grade/querylib.php');

        $grade_item = grade_item::fetch_course_item($courseid);

        if ($grade = grade_get_course_grade($userid, $courseid)) {
            if (is_null($grade->grade)) return 0;

            $min = $grade_item->grademin;
            $max = $grade_item->grademax;
            if ($min == $max) {
                return 0;
            }
            $value = $grade_item->bounded_grade($grade->grade);
            $percentage = (($value-$min)*100)/($max-$min);
            return $percentage;
        }

        return 0;
    }
}
