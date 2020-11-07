# wp-plugin-framework-core
*Foundation for advanced WordPress plugin development by Christoph Ehlers & Kevin Wellmann* 
## Developer notes

### Install dependencies
Using composer to install plugin dependencies:
`composer install`

### Test and code-coverage
There are several scripts to helps You testing Your code or check code-style:

|Scriptname|Example|Description|
|---|---|---|
|build:cc               | composer run build:cc                 |Generates the coverage files|
|build:cs-diff          | composer run build:cs-dif             |Generates a file that contains difference between Your and a fixed version of code|
|check:cs               | composer run check:cs                 |Check code-style|
|fix:cs                 | composer run fix:cs                   |Fix code-style|
|patch:cs-diff          | composer run patch:cs-diff            |Using generated .diff-file to fix code-style|
|start&#x2011;server:cc | composer run start&#x2011;server:cc   |Run local webserver to display generated coverage files|
|test                   | composer run test                     |Run unit-tests|