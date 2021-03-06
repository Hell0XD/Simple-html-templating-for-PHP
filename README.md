﻿# Simple-html-templating-for-PHP



#### Examples


##### 1. Replacing default value with new one

###### index.html
```html
<h1>
  <!--{{Text}}-->Default text<!--{{/Text}}-->
</h1>
```


###### index.php

```php
	require 'path/to/engine.php';
    
    // Init new engine with path to html file to load
    $engine = new Engine("path/to/index.html");
    
    $variables = array(
    	"Text" => "Hello world"
    );
    
    echo $engine->generate_html($variables);
    
    
    /*
    Prints
    
    <h1>
  		Hello world
    </h1>
    */
```

##### 2. Using blocks to replicate block with new value

###### index.html
```html
<!--{{#block}}-->
<p>
	<!--{{Text}}-->Default text<!--{{/Text}}-->
</p>
<!--{{/#block}}-->
```

###### index.php

```php
	require 'path/to/engine.php';
    
    // Init new engine with path to html file to load
    $engine = new Engine("path/to/index.html");
    
    $variables = array(
    	"block" => array(
        	array("Text" => "Ahoj"),
            array("Text" => "Světe")
        )
    );
    
    echo $engine->generate_html($variables);
    
    
    /*
    Prints
    
    
    <p>
        Ahoj
    </p>
    <p>
       Světe
    </p>
    
    */
```
