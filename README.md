# CSV Mate
PHP utility library as a CSV helper

# Installation
`composer require alaa-almaliki/csv-mate:dev-master`

# Documentation
## Load
```
require_once 'vendor/autoload.php';
$csv = new CsvMate\Csv();
// CSV Reader
$reader = $csv->getReadCsv(['file_path' => __DIR__  . '/mycsv.csv']);
```
## Rename CSV Headers to the names required
```
$reader->renameHeaders(['policyID' => 'policy_id', 'statecode' => 'state_code']);
```
## All Data
```
// get all data 
$allData = $reader->getData();
print_r($allData);
```
## Get associate data with headers
```
$associateData = $reader->getAssocData();
print_r($associateData);
```

## Get one column
```
$oneColumn = $reader->getColumn('policyID');
print_r($oneColumn);
```

## Get one column by index
```
$oneColumn = $reader->getColumn(0);
print_r($oneColumn);
```

## Get one column by a renamed header 
```
$oneColumn = $reader->getColumn('policy_id');
print_r($oneColumn);
```

## Get specified columns 
```
$columns = $reader->getColumns(['policyID', 'statecode', 'hu_site_limit', 'county']);
print_r($columns);
```

## Get specified columns by indexes
```
$columns = $reader->getColumns([0, 1, 4, 2]); // $reader->getColumns(['policyID', 'statecode', 'hu_site_limit', 'county'])
print_r($columns);
```

## Get specified columns by renamed headers
```
$columns = $reader->getColumns(['policy_id', 'state_code', 'hu_site_limit', 'county']);
print_r($columns);
```

## Add conditions to how the data should be returned 
### policy id in specified values
```
$reader->where('policyID', ['eq' => ['119736', '448094', '206893', '333743', '172534']]);
$filteredData = $reader->getData();
print_r($filteredData);
```
### policy id in specified values - passed as index 0

```
$reader->where('0', ['eq' => ['119736', '448094', '206893', '333743', '172534']]);
$filteredData = $reader->getData();
print_r($filteredData);
```
### policy id in specified values as associated data
```
$reader->where('policyID', ['eq' => ['119736', '448094', '206893', '333743', '172534']]);
$filteredData = $reader->getAssocData();
print_r($filteredData);
```
### policy id as renamed to policy_id in specified values as associated data
```
$reader->where('policy_id', ['eq' => ['119736', '448094', '206893', '333743', '172534']]);
$filteredData = $reader->getAssocData();
print_r($filteredData);
```
### county equals to CLAY COUNTY
```
$reader->where('county', ['eq' => ['CLAY COUNTY']]);
$filteredData = $reader->getAssocData();
print_r($filteredData);
```
### more conditions
```
$reader->where('county', ['eq' => ['CLAY COUNTY']])
    ->where('policy_id', ['in' => ['119736', '448094', '206893', '333743', '172534']])
    ->where('eq_site_limit', ['neq' => ['498960']]);

$filteredData = $reader->getAssocData();
print_r($filteredData);
```
### More more conditions
```
$reader->where('county', ['eq' => ['CLAY COUNTY']])
    ->where('policy_id', ['lt' => ['1000000']])
    ->where('eq_site_deductible', ['gt' => '0']);

$filteredData = $reader->getAssocData();
print_r($filteredData);
```

## Select columns and add conditions 
```
$reader->selectColumns([
    'policyID',
    'statecode',
    'hu_site_limit',
    'county',
    'tiv_2011',
    'fr_site_deductible',
    'hu_site_deductible',
    'point_longitude',
    'point_latitude'
]);

$reader->where('county', ['eq' => ['CLAY COUNTY']])
    ->where('policy_id', ['lt' => ['1000000']])
    ->where('eq_site_deductible', ['gt' => '0']);

$filteredData = $reader->getAssocData();
print_r($filteredData);
```
### Another way to select by renamed headers
```
$reader->selectColumns([
    'policy_id',
    'state_code',
    'hu_site_limit',
    'county',
    'tiv_2011',
    'fr_site_deductible',
    'hu_site_deductible',
    'point_longitude',
    'point_latitude'
]);

$reader->where('county', ['eq' => ['CLAY COUNTY']])
    ->where('policy_id', ['lt' => ['1000000']])
    ->where('eq_site_deductible', ['gt' => '0']);

$filteredData = $reader->getAssocData();
print_r($filteredData);
```

## CSV Writer

```
$dataToWrite = [
    ['first_name', 'last_name', 'email', 'occupation'],
    ['Alaa', 'Al-Maliki', 'alaa.almaliki@gmail.com', 'Software Engineer'],
    ['John', 'Doe', 'john.doe@gmail.com', 'Test Analyst'],
    ['Kate', 'Foxon', 'kate.foxon@gmail.com', 'Project Manager'],
    ['Allan', 'McGain', 'allan.mcgain@gmail.com', 'Database Administrator'],
    ['Andrew', 'James', 'andrew.james@gmail.com', 'System Analyst'],
    ['Jamie', 'Doug', 'jamie.doug@gmail.com', 'Technical Lead'],
    ['Karen', 'Hostfield', 'karne.hostfield@gmail.com', 'System Architect'],
];

$writer = $csv->getWriteCsv(['file_path' => __DIR__ . '/dataCopy.csv']);
$writer->writeData($dataToWrite);

```