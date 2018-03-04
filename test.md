# Test

A file used for testing the modifications made to the Parsedown class and the classes that I have created.

## Test 1

The images below are used in testing the ability to adjust the paths to point to the images' location. Two types linking are tested here - 

* Markdown image link
* Embedded HTML

**NOTES:** GitHub ignores any styling in the tag. It will also convert any `<img>` tag to a link to the image.

### Markdown Image Link

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

