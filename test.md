# markdown-render Tests

A file used for testing the modifications made to the Parsedown class and the classes that I have created.

## Test 1

The images below are used in testing the ability to adjust the paths to point to the images' location. Three types linking are tested here - 

* [Markdown Embedded Image](#markdown-embedded-image)
* [Markdown Inline Resource Link](#markdown-inline-resource-link)
* [Embedded HTML](#embedded-html)

*Anchors are tested in the links above, this required a modification to `Parsedown.php`*.

**NOTES:** GitHub ignores any styling in embedded HTML except for `width` and `height` attributes. It will also convert any `<img>` tag to a link to the image. However if the `<img>` tag is wrapped in a `<p>` it will wrap the `<img>` in an `<a href=...>`.

### Markdown Embedded Image

Image Markdown - `![Image, relative path](./mdimg/electric-globe-600x400.jpg)`

![Image, relative path](./mdimg/electric-globe-600x400.jpg)

### Markdown Inline Resource Link

Markdown Resource Link - `[github.json](./github.json)`

[github.json](./github.json)

### Embedded HTML

**Centered using a `<p>` tag :**

```html
<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" title="sample image #1" width="50%">
</p>
```

<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" title="sample image #1" width="50%">
</p>

**Just an `<img>` tag :**

```html
<img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" title="sample image #2" width="50%">
```

<img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" title="sample image #2" width="50%">

### Test 1 Expected Results

* Anchor Tags - clicking on the heading links should cause the rendered page to scroll to the identified position.

* Inline Image Link - The `src` attribute will become an *absolute* path to the image **and** the `alt` attribute will be modified to be `"Image, absolute path"`. **NOTE :** The `alt` attribute is not related to the `[Image, relative path]` portion of the Markdown Link. However the `title` attribute will contain the contents of the `[Image, relative path]` portion and will be seen if hovered over.

* Inline Resource Link - The `href` attribute will reference the *page* containing the resource on GitHub, and should open a new tab when clicked.

* Embedded HTML - The `src` attribute will become an *absolute* path to the image. And it will retain all other attributes that were present in the source.

**NOTE :** All rendered images should retain all attributes, including `title`.

## Test 2

This test will be used to insure that other "void" elements (*as listed in* `Parsedown.php`*-*`$voidElements`) behave as expected.

* `<hr>` test - 

There should be a horizontal rule below this line.

<hr>

There should be a horizontal rule above this line.


* `<br>` test - 
Text before `<br>` - <br> - text after `<br>`.

### Test 2 Expected Results

Elements that are not set to be modified should be passed through without any changes or errors.

## Test 3

This test will contain several *code blocks*. And each will specify the language, the result will be the addition of the `language-html` class to the `<pre>` and/or `<code>` tags.

**C++**

```cpp
/*
    Perform any required initialization steps after the config files
    have been read and parsed.
*/
void setupInit()
{
    // if we're not indicating an error the continue with the 
    // initialization of the UDP functionality...
    if(toggInterv == TOGGLE_INTERVAL) 
    {
        if(!initUDP()) 
        {
            printError(String(__func__), "UDP init failed!");
            toggInterv = ERR_TOGGLE_INTERVAL;
        }
    }
}
```
<br>

**JavaScript**

```javascript
/*
    Server Listening has begun
*/
server.on('listening', () => {
    const address = server.address();
    consolelog(`UDP server listening on - ${address.address}:${address.port}`);
});

// must tell the server to listen on the port and address
server.bind(srvcfg.port, srvcfg.host);
```
<br>

**HTML**

```html
<!-- center aligned image -->
<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" title="sample image #1" width="50%">
</p>
```
<br>

**CSS**

```css
/* Inline code */
:not(pre) > code[class*="language-"] {
  border-radius: .3em;
  border: .13em solid hsl(0, 0%, 33%); /* #545454 */
  box-shadow: 1px 1px .3em -.1em black inset;
  padding: .15em .2em .05em;
  white-space: normal;
}
```
<br>

**PHP**

```php
<?php
/*
    Rebuild the tag with all attributes
*/
function rebuildElement($tagname)
{
    $markup = "<$tagname ";
    foreach($this->attrs as $key => $value)
    {
        $markup = $markup . "$key=\"$value\" ";
    }
    // close the tag and return it
    $markup = $markup . ">";
    return $markup;
}
?>
```
<br>

### Test 3 Expected Results

The results depend on settings that are kept in the Markdown document's configuration file. This document's file is `test.json`. 

The appearance of the code blocks can be altered by first editing `test.json`. The following will produce yellow colored text in a fixed spaced font, code blocks will have a border around them.

```json
{
    "codecolor": false,
    "codecolorfiles": ""
}
```

I've included [PrismJS](http://prismjs.com/) so that code blocks can be colored. To enable - 


```json
{
    "codecolor": true,
    "codecolorfiles": "./codecolor.json"
}
```

The `codecolor.json` file contains the necessary CSS and JavaScript tags for using [PrismJS](http://prismjs.com/) - 

```json
{
    "links":[
        "<link href=\"./assets/prism/prism.css\" rel=\"stylesheet\"/>",
        "END"
    ],
    "scripts":[
        "<script src=\"./assets/prism/prism.js\"></script>",
        "END"
    ]
}
```
<br>

Individual "code coloring" JSON files can be created as needed, and document configuration files can specify their own file. This was done due to the nature of the [PrismJS](http://prismjs.com/) dowload process will provide differnt CSS/JS files depending upon selected options. If addtional CSS or JS files are needed they can be inserted into the `"links"` or `"scripts"` arrays before `"END"`. If using the [PrismJS CDN](https://cdnjs.com/libraries/prism) those URLs can also be located in this file.


