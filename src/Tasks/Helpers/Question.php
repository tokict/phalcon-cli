<?php

namespace Danzabar\CLI\Tasks\Helpers;

use Danzabar\CLI\Tasks\Helpers\Helper;

/**
 * The Question helper class
 *
 * @package CLI
 * @subpackage Tasks\Helpers
 * @author Dan Cox
 */
class Question extends Helper
{
    /**
     * The error message displayed when the user selects the wrong choices.
     *
     * @var string
     */
    protected $wrongChoiceErrorMessage = 'The answer you selected is invalid.';

    /**
    *Save the question here so we can repeat it in case of bad choice
     */
    protected $lastQuestion = null;

    /**
     * Asks a basic question and returns the response.
     * @param $question | Asked question
     * @param $required | It will ask again if no answer is provided and no default
     * @param $default | Default answer if none provided
     * @return String
     */
    public function ask($question, $required = true, $default = null)
    {
        $this->lastQuestion = $question;
        $def = $default?' ['.$default.']':'';
        $this->output->writeln(Color::paint($question.$def, Color::yellow));

         $answer = $this->input->getInput();
         if(!$answer && $required){
             if($default){
                 return $default;
             }
             $this->ask($question, true, $default);
         }

         return $answer;

    }

    /**
     * Sets the error message that displays when a user picks the wrong answer
     *
     * @return void
     */
    public function setChoiceError($error)
    {
        $this->wrongChoiceErrorMessage = Color::paint($error, Color::red);
    }

    /**
     * The choice question, pick a single choice.
     * @param $question | Asked question
     * @param $choice | Available choices
     * @param $allowedMultiple | Are multiple comma separated answers allowed
     * @param $default | Default value if no answer provided
     * @return String
     */
    public function choice($question, $choices = array(), $allowedMultiple = false, $default = null)
    {
        $this->lastQuestion = $question;
        $def = $default?' ['.$default.']':'';
        $this->output->writeln(Color::paint($question. $def, Color::yellow));

        foreach ($choices as $key => $choice) {
            $this->output->write($key . ' => ');
            $this->output->writeln($choice . PHP_EOL);
        }

        $answer = $this->input->getInput();

        //If there is no answer and default is set, use default as answer
        !$answer && $default?$answer = $default:null;


        $valid = $this->validateChoices($answer, $choices, $allowedMultiple, $default);

        if ($valid !== false) {
            return $valid;
        }

        $this->output->writeln($this->wrongChoiceErrorMessage);

        $this->choice($question, $choices, $allowedMultiple);
    }

    /**
     * A quick function to allow multiple answers on a choice question
     *
     * @return void
     */
    public function multipleChoice($question, $choices = array(), $allowedMultiple = false, $default = null)
    {
        return $this->choice($question, $choices, $allowedMultiple, $default);
    }

    /**
     * Checks that the answer is present in the list of choices
     *
     * @return Boolean
     */
    public function validateChoices($answer, $choices, $allowedMultiple = false)
    {
        if ($allowedMultiple) {
            return $this->validateMultipleChoice($answer, $choices);
        } else {
            return $this->validateSingleChoice($answer, $choices);
        }
    }

    /**
     * Validates a single answer
     *
     * @return Boolean|String
     */
    public function validateSingleChoice($answer, $choice)
    {
        //If the answer is number check if there is an index in answers
        if (is_numeric($answer) && !isset($choice[$answer])) {
            return false;
        }

        if (!is_numeric($answer) && !in_array(trim($answer), $choice)) {
            return false;
        }

        return is_numeric($answer)?$choice[$answer]:$answer;
    }

    /**
     * Validates multiple answers
     *
     * @return Boolean|String
     */
    public function validateMultipleChoice($answer, $choice)
    {
        $answers = explode(',', $answer);

        foreach ($answers as $ans) {

            //If the answer is number check if there is an index in answers
            if (is_numeric($ans) && !isset($choice[(int)$ans])) {
                return false;
            }

            if (!is_numeric($ans) && !in_array(trim($ans), $choice)) {
                return false;
            }

            if(is_numeric($ans)){
                $a = $choice[$ans];
            }else{
                $a = $ans;
            }
            $formatedAnswers[] = trim($a);
        }

        return $formatedAnswers;
    }
} // END class Question extends Helper
