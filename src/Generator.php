<?php

namespace SheetFaker;

use Box\Spout\Common\Type;
use Faker\Generator as Faker;
use Box\Spout\Writer\WriterInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Generates fake values & outputs the files.
 */
class Generator
{
    /**
     * Output interface.
     *
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * File writer instance.
     *
     * @var \Box\Spout\Writer\WriterInterface
     */
    protected $writer;

    /**
     * Fake value generator instance (Faker).
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Column names / Faker formatters to use
     * to create the data.
     *
     * @var array
     */
    protected $columns;

    /**
     * Number of rows to create.
     *
     * @var int
     */
    protected $rows;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $output
     * @param \Box\Spout\Writer\WriterInterface WriterInterface $writer
     * @param \Faker\Generator $faker
     */
    public function __construct(SymfonyStyle $output, WriterInterface $writer, Faker $faker)
    {
        $this->output = $output;

        $this->writer = $writer;

        $this->faker = $faker;
    }

    /**
     * Column setter.
     *
     * @param array $columns
     * 
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Row setter.
     * 
     * @param int $rows
     * 
     * @return $this
     */
    public function setRows($rows)
    {
        $this->rows = intval($rows);

        return $this;
    }

    /**
     * Thows an exception if the specified path is not valid.
     * 
     * @param string $path
     * 
     * @return void
     * 
     * @throws \SheetFaker\PathException
     */
    protected function throwIfInvalidPath($path)
    {
        if (is_writable(dirname($path))) {

            if ($file_exists = file_exists($path)) {

                if ($this->output->confirm('The file exists, do you want to remove it? [y|N]', false)) {
                    unlink($path);
                    return;
                }

                throw new PathException("$path the specified file already exists.");
            }

            return;

        }

        throw new PathException("$path is not writable.");
    }

    /**
     * Validates the path, opens the writers file 
     * stream and adds the row headers.
     * 
     * @param string $path
     * 
     * @return void
     */
    protected function createFile($path)
    {
        $this->throwIfInvalidPath($path);

        $this->writer->openToFile($path);

        $this->writer->addRow($this->getPrettyColumns());
    }

    /**
     * Gets pretty names for the column headers 
     * from the Faker formatter names.
     * 
     * @return array
     */
    protected function getPrettyColumns()
    {
        $columns = $this->columns;

        $uppercase = function ($v) { 
            return ucfirst($v); 
        };

        foreach ($columns as $k => $v) {
            $pieces = preg_split('/(?=[A-Z])/', $v);
            $pieces = array_map($uppercase, $pieces);
            $columns[$k] = implode(' ', $pieces);
        }

        return $columns;
    }

    /**
     * Writes a frow of fake data to the via the writer.
     * 
     * @return void
     */
    protected function writeRow()
    {
        $row = [];

        foreach ($this->columns as $column) {
            $row[] = $this->faker->$column;
        }

        $this->writer->addRow($row);
    }

    /**
     * Generate the data while outputting updates.
     *
     * @param string $path
     *
     * @return void
     */
    public function generate($path)
    {
        $bar = new ProgressBar($this->output, $this->rows);

        $this->output->writeln("<info>Creating file with $this->rows rows at $path.</info>");

        $this->createFile($path);

        for ($i = 0; $i < $this->rows; $i++) {
            $this->writeRow();
            $bar->advance();
        }

        $this->writer->close();

        $bar->finish();

        $this->output->writeln(PHP_EOL . '<info>Complete.</info>');
    }
}