#Sheet Faker
As it says on the tin, Sheet Faker allows you to efficiently create large CSV, ODS & XLSX files containing fake data for testing purposes.

Credit to [Faker](https://github.com/fzaninotto/Faker) package which is used to generate the data and [Box's Sprout](https://github.com/box/spout) to create the files.

## Usage
    # Clone the repo (or download a zipball):
    git clone https://github.com/olsgreen/sheet-faker

    cd sheet-faker

    # Install via composer
    composer install

    # Run
    ./faker generate format path --rows=n --columns=formatter1,formatter2,formatter3
    
## Arguments & Options
- format - CSV, ODS or XLSX
- path   - Fully qualified writable path to save the file.
- --rows - The number of rows to generate.
- --columns - A comma separated list of columns specified using Faker formatter names see [here](http://bit.ly/1NyNeUH) for a full list of possible data types.

### Example:
`./faker generate csv /data/my.csv --rows=1000 --columns=firstName,lastName,email`

The CSV file generated would be saved to /data/my.csv, have the column headers of First Name, Last Name & Email and contain 1000 rows of fake data.

## License
Released under the MIT Licence. See the bundled LICENSE file for details.