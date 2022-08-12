<?php

namespace Ffcms\Console;

use Ffcms\Core\Helper\Type\Str;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class Command. Extend symfony command to simplify usage
 * @package Ffcms\Console
 */
class Command extends SymfonyCommand
{
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;
    /** @var string|null */
    public $dbConnection = null;

    /**
     * Set database connection name to use not with only default connection
     * @param string|null $name
     */
    public function setDbConnection($name = null)
    {
        $this->dbConnection = $name;
    }

    /**
     * Ask string param from stdin php input
     * @param string $question
     * @param string|null $default
     * @return string
     */
    public function ask($question, $default = null)
    {
        $que = new Question($question, $default);
        $helper = new SymfonyQuestionHelper();
        return $helper->ask($this->input, $this->output, $que);
    }

    /**
     * Ask confirmation for question (yes/no)
     * @param string $question
     * @param bool $default
     * @return string
     */
    public function confirm($question, $default = false)
    {
        $que = new ConfirmationQuestion($question, $default);
        $helper = new SymfonyQuestionHelper();
        return $helper->ask($this->input, $this->output, $que);
    }

    /**
     * Catch input & output instances inside class
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        return parent::run($input, $output);
    }

    /**
     * Get input option value or ask it if empty
     * @param string $option
     * @param string $question
     * @param string|null $default
     * @return string
     */
    public function optionOrAsk($option, $question, $default = null)
    {
        $value = $this->input->getOption($option);
        if ($value === null || Str::likeEmpty($value)) {
            $value = $this->ask($question, $default);
        }

        return $value;
    }

    /**
     * Get input option value
     * @param string $name
     * @return string|null
     */
    public function option($name) {
        return $this->input->getOption($name);
    }
}