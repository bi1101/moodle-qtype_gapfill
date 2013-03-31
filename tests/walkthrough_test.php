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
 * This file contains tests that walks a question through the interactive
 * behaviour.
 *
 * @package    qtype
 * @subpackage gapfill
 * @copyright  2012 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/question/type/gapfill/tests/helper.php');

/**
 * Unit tests for the gapfill question type.
 * Not complete, needs more examples.
 * @copyright  2012 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_gapfill_walkthrough_test extends qbehaviour_walkthrough_test_base {

    public function test_deferred_feedback_unanswered() {

        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;
        $this->start_attempt_at_question($gapfill, 'deferredfeedback', $maxmark);
        /* Check the initial state. */
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_step_count(1);

           $this->check_current_output(
                $this->get_contains_marked_out_of_summary(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_validation_error_expectation(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_no_hint_visible_expectation());

        // Save an  correct response.
        $this->process_submission(array('p1' => '', 'p2' => ''));
        $this->check_step_count(2);
        $this->check_current_state(question_state::$todo);

        $this->quba->finish_all_questions();
        $this->check_step_count(3);
        $this->check_current_state(question_state::$gaveup);
        $this->check_current_mark(null);
    }

    public function test_deferred_with_correct() {
        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;
        $this->start_attempt_at_question($gapfill, 'deferredfeedback', $maxmark);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_step_count(1);

        // Save an  correct response.
        $this->process_submission(array('p1' => 'cat', 'p2' => 'mat'));
        $this->check_step_count(2);
        $this->check_current_state(question_state::$complete);

        $this->quba->finish_all_questions();
        $this->check_step_count(3);
        $this->check_current_state(question_state::$gradedright);
        $this->check_current_mark(2);
        $this->quba->finish_all_questions();
    }

    public function test_deferred_with_incorrect() {

        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;
        $this->start_attempt_at_question($gapfill, 'deferredfeedback', $maxmark);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_step_count(1);

        // Save an  correct response.
        $this->process_submission(array('p1' => 'dog', 'p2' => 'cat'));
        $this->check_step_count(2);
        $this->check_current_state(question_state::$complete);

        $this->quba->finish_all_questions();
        $this->check_step_count(3);
        $this->check_current_state(question_state::$gradedwrong);
        $this->check_current_mark(0);
    }

    public function test_deferred_with_partially_correct() {

        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;
        $this->start_attempt_at_question($gapfill, 'deferredfeedback', $maxmark);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_step_count(1);

        // Save an  correct response.
        $this->process_submission(array('p1' => 'cat', 'p2' => 'dog'));
        $this->check_step_count(2);
        $this->check_current_state(question_state::$complete);

        $this->quba->finish_all_questions();
        $this->check_step_count(3);
        $this->check_current_state(question_state::$gradedpartial);
        $this->check_current_mark(1);
    }

    public function test_interactive_with_correct() {

        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;
        $this->start_attempt_at_question($gapfill, 'interactive', $maxmark);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);

        $this->check_step_count(1);

        $this->check_current_output(
                $this->get_contains_marked_out_of_summary(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_validation_error_expectation(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_no_hint_visible_expectation());

        // Save a  correct response.
        $this->process_submission(array('p0' => 'cat', 'p1' => 'mat'));
        $this->check_step_count(2);

        $this->check_current_state(question_state::$todo);

        $this->check_current_output(
                    $this->get_contains_marked_out_of_summary(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_validation_error_expectation(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_no_hint_visible_expectation());

        // Submit saved response.
        $this->process_submission(array('-submit' => 1, 'p1' => 'cat', 'p2' => 'mat'));
        $this->check_step_count(3);
        // Verify.
        $this->check_current_state(question_state::$gradedright);

        $this->check_current_output(
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_does_not_contain_validation_error_expectation(),
                $this->get_does_not_contain_try_again_button_expectation(),
                $this->get_no_hint_visible_expectation());

        $this->check_current_mark(2);
        // Finish the attempt.
        $this->quba->finish_all_questions();
        $this->check_current_state(question_state::$gradedright);
    }

    
    public function test_interactive_wildcard_with_correct() {
        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill',array('cat|dog', 'mat'));
        $maxmark = 2;
        
     

        $this->start_attempt_at_question($gapfill, 'interactive', $maxmark);
        //$this->quba->set_preferred_behaviour('interactive');


        // Check the initial state.
        $this->check_current_state(question_state::$todo);

        $this->check_step_count(1);


        // Save a  correct response.
        $this->process_submission(array('p0' => 'cat', 'p1' => 'mat'));
        $this->check_step_count(2);

        $this->check_current_state(question_state::$todo);
        // Submit saved response.
        $this->process_submission(array('-submit' => 1, 'p1' => 'cat', 'p2' => 'mat'));
        $this->check_step_count(3);

        // Verify.
        $this->quba->finish_all_questions();
        $this->check_current_state(question_state::$gradedright);

        $this->check_current_mark(2);
        // Finish the attempt.
    }
    public function test_immediatefeedback_with_correct() {

        // Create a gapfill question.
        $gapfill = qtype_gapfill_test_helper::make_question('gapfill');
        $maxmark = 2;

        $gapfill->showanswers=true;
        $this->start_attempt_at_question($gapfill, 'immediatefeedback', $maxmark);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);

        $this->check_step_count(1);

        // Save a  correct response.
        $this->process_submission(array('p0' => 'cat', 'p1' => 'cat'));
        $this->check_step_count(2);

        $this->check_current_state(question_state::$todo);
        // Submit saved response.
        $this->process_submission(array('-submit' => 1, 'p1' => 'cat', 'p2' => 'mat'));
        $this->check_step_count(3);
        // Verify.
        $this->quba->finish_all_questions();
        $this->check_current_state(question_state::$gradedright);

        $this->check_current_mark(2);
        // Finish the attempt.
    }

}
