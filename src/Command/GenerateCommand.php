<?php

namespace SheetFaker\Command;

use SheetFaker\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;;

/**
 * Facilitates adding a generate command to a symfony console application.
 */
class GenerateCommand extends Command
{
    /**
     * Setup the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate a csv, xlsx or ods file containing dummy data.')
            ->addArgument(
                'format',
                InputArgument::REQUIRED,
                'File format (csv, xlsx or ods)'
            )
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Fully qualified path to output file.'
            )
            ->addOption(
                'locale',
                null,
                InputOption::VALUE_OPTIONAL,
                'Locale for data generation, default en_US. (see http://bit.ly/1NTquJb)'
            )
            ->addOption(
                'columns',
                null,
                InputOption::VALUE_OPTIONAL,
                'Columns to generate specified using Faker formatter names. (see http://bit.ly/1NyNeUH)'
            )
            ->addOption(
                'rows',
                10,
                InputOption::VALUE_OPTIONAL,
                'The number of rows to generate.'
            );
    }

    /**
     * Makes a random filename in the app directory.
     *
     * @param string $format
     * 
     * @return string
     */
    protected function makePath($format)
    {
        $filename = 'faked-' . time() . '.' . $format;

        return SF_BASE_PATH . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Bulids a generator, passes the relevant vars and
     * then generates the output.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * 
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($command, $format, $path) = array_values($input->getArguments());
        list($locale, $columns, $rows) = array_values($input->getOptions());

        if (!$path) {
            $path = $this->makePath($format);
        }

        $output = new SymfonyStyle($input, $output);

        $generator = (new Builder($output))->make($format, $locale, $rows, $columns);
        $generator->generate($path);
    }
}