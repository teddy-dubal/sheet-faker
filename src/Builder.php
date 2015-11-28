<?php

namespace SheetFaker;

use Faker\Factory;
use Box\Spout\Common\Type;
use InvalidArgumentException;
use Box\Spout\Writer\WriterFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Builds Generator class instances.
 */
class Builder
{
    /**
     * Constructor.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $output
     */
    public function __construct(SymfonyStyle $output)
    {
        $this->output = $output;
    }

    /**
     * Make a new Generator instance.
     *
     * @param string $format
     * @param int    $rows
     * @param array  $columns
     * 
     * @return \SheetFaker\Generator
     */
    public function make($format, $rows = null, $columns = null)
    {
        date_default_timezone_set(@date_default_timezone_get());

        if (!$rows) {
            $rows = 10;
        }

        if (!$columns) {
            $columns = ['firstName', 'lastName', 'company', 'email', 'phoneNumber'];
        } elseif (!is_array($columns)) {
            $columns = explode(',', $columns);
        }

        $faker = Factory::create();
        $writer = $this->writerFromFormat($format);

        return (new Generator($this->output, $writer, $faker))
            ->setRows($rows)
            ->setColumns($columns);
    }

    /**
     * Creates a Writer from file extension.
     *
     * @param string $format
     * 
     * @return \Box\Spout\Writer\WriterInterface
     */
    protected function writerFromFormat($format)
    {
        switch ($format)
        {
            case 'ods':
                return WriterFactory::create(Type::ODS);
            case 'xlsx':
                return WriterFactory::create(Type::XLSX);
            case 'csv':
                return WriterFactory::create(Type::CSV);
            default:
                throw new InvalidArgumentException("Invalid output format [$format] specified.");
        }
    }
}