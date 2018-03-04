# markdown-render Tests

A file used for testing the modifications made to the Parsedown class and the classes that I have created.

## Test 1

The images below are used in testing the ability to adjust the paths to point to the images' location. Two types linking are tested here - 

* Markdown image link
* Embedded HTML

**NOTES:** GitHub ignores any styling in the tag. It will also convert any `<img>` tag to a link to the image. 

### Markdown Inline Image Link

Image link - `![Image, relative path](./mdimg/electric-globe-600x400.jpg)`

![Image, relative path](./mdimg/electric-globe-600x400.jpg)

### Embedded HTML

**Centered using a `<p>` tag :**

```
<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="Circuit Schematic" txt="Circuit Schematic" width="50%">
</p>
```

<p align="center">
  <img src="./mdimg/electric-globe-600x400.jpg" alt="Circuit Schematic" txt="Circuit Schematic" width="50%">
</p>

**Just an `<img>` tag :**

```
<img src="./mdimg/electric-globe-600x400.jpg" alt="Circuit Schematic" txt="Circuit Schematic" width="50%">
```

<img src="./mdimg/electric-globe-600x400.jpg" alt="Circuit Schematic" txt="Circuit Schematic" width="50%">

### Test 1 Expected Results

* Inline Image Link - The `src` attribute will become an *absolute* path to the image **and** the `alt` attribute will be modified to be `"Image, absolute path"`.

* Embedded HTML - The `src` attribute will become an *absolute* path to the image, it will retain all other attributes that were present in the source.


## Test 2

This test will be used to insure that all "void" elements (*as listed in* `Parsedown.php`*-*`$voidElements`).

* `<hr>` - 

<hr>

* `<br>` - 
Text before `<br>` - <br> - text after `<br>`.

### Test 2 Expected Results

