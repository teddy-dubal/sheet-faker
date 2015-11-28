#Sheet Faker
As it says on the tin, Sheet Faker allows you to efficiently create large CSV, ODS & XLSX files containing fake / test data.

Credit to the [Faker](https://github.com/fzaninotto/Faker) package which is used to generate the data and [Box's Sprout](https://github.com/box/spout) to create the files.

## Usage
    # Install via composer
    composer create-project olsgreen/sheet-faker
    
    cd sheet-faker

    # Run
    ./faker generate csv

Not got composer  installed? See [here](https://getcomposer.org/doc/00-intro.md).

## Arguments & Options
- format - CSV, ODS or XLSX
- path   - Fully qualified writable path to save the file.
- --locale - The desired locale of the test data see [here](http://bit.ly/1NTquJb) for a list of available locales.
- --rows - The number of rows to generate.
- --columns - A comma separated list of columns specified using Faker formatter names see [here](http://bit.ly/1NyNeUH) for a full list of possible data types.

### Example:
`./faker generate csv /data/my.csv --rows=1000 --columns=firstName,lastName,email --locale=fr_FR`

The CSV file generated would be saved to /data/my.csv, have the column headers of First Name, Last Name & Email and contain 1000 rows of fake data based on French locale.

## License
Released under the MIT Licence. See the bundled LICENSE file for details.