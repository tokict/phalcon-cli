<?php

use Danzabar\CLI\Command,
	Danzabar\CLI\Traits,
	Danzabar\CLI\Input\InputArgument,
	Danzabar\CLI\Input\InputOption;

/**
 * The input task for testing input
 *
 * @package CLI
 * @subpackage Tests
 * @author Dan Cox
 */
class InputTask extends Command
{
	/**
	 * Setup required arguments and options
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup($action)
	{
		switch($action)
		{
			case 'mainAction':
				$this->option->addExpected('verbose', InputOption::Optional);
				break;
			case 'requiredAction':
				$this->argument->addExpected('email', InputArgument::Required);
				break;
			case 'exceptionAction':
				$this->argument->addExpected('value', 'string');
				break;
			case 'validationAction':
				$this->argument->addExpected('value', Array(InputArgument::Required, InputArgument::Alpha));
				break;
		}
	

	}

	/**
	 * The main action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function mainAction()
	{
		if(isset($this->option->verbose))
		{
			$this->output->writeln('verbose mode activated');
		} else
		{
			$this->output->writeln('...');
		}
	}

	/**
	 * Another action
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function requiredAction()
	{
		$this->output->writeln($this->argument->email);
	}

	/**
	 * Action to test validation
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function validationAction()
	{
		$this->output->writeln($this->argument->value);
	}


} // END class InputTask extends Command

