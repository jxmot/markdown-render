# markdown-render Tests

A file used for testing the modifications made to the Parsedown class and the classes that I have created.

## Test 1

The images below are used in testing the ability to adjust the paths to point to the images' location. Three types linking are tested here - 

* [Markdown Inline Image Link](#markdown-inline-image-link)
* [Markdown Inline Resource Link](#markdown-inline-resource-link)
* [Embedded HTML](#embedded-html)

*Anchors are tested in the links above, requried a mondification to `Parsedown.php`*.

**NOTES:** GitHub ignores any styling in embedded HTML except for `width` and `height` attributes. It will also convert any `<img>` tag to a link to the image. However if the `<img>` tag is wrapped in a `<p>` it will wrap the `<img>` in an `<a href=...>`.

### Markdown Inline Image Link

Image link - `![Image, relative path](./mdimg/electric-globe-600x400.jpg)`

![Image, relative path](./mdimg/electric-globe-600x400.jpg)

### Markdown Inline Resource Link

[github.json](./github.json)

### Embedded HTML

**Centered using a `<p>` tag :**

```
<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" txt="sample image" width="50%">
</p>
```

<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" txt="sample image" width="50%">
</p>

**Just an `<img>` tag :**

```
<img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" txt="sample image" width="50%">
```

<img src="./mdimg/electric-globe-600x400.jpg" alt="sample image" txt="sample image" width="50%">

### Test 1 Expected Results

* Inline Image Link - The `src` attribute will become an *absolute* path to the image **and** the `alt` attribute will be modified to be `"Image, absolute path"`.

* Embedded HTML - The `src` attribute will become an *absolute* path to the image, it will retain all other attributes that were present in the source.


## Test 2

This test will be used to insure that all "void" elements (*as listed in* `Parsedown.php`*-*`$voidElements`).

* `<hr>` test - 

There should be a horizontal rule below this line.

<hr>

There should be a horizontal rule above this line.


* `<br>` test - 
Text before `<br>` - <br> - text after `<br>`.

### Test 2 Expected Results

Elements that are not set to be modified should be passed through without any changes or errors.

