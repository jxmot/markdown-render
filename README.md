# markdown-render

A PHP project that renders markdown files from GitHub repositories. Based on [Parsedown](http://parsedown.org)

# History

Initially I was looking for a way to render *markdown* files for a blog project I was working on. The intent at that time was to be able to render small markdown files that were to be used as blog entries.

However, later on I found that I wanted to render my GitHub README and project documentation files for use on my personal website. And the intent there was to have the ability to showcase README content without having to direct visitors to my GitHub account.

I had investegated a number of potential solutions and determined that [Parsedown](http://parsedown.org) would work best for my applications. 

# Features

The following features are *enhancements* to the operation of the original Parsedown - 

* Extended with the `ParsedownModify` class. Added two functions - 
    * `modifyVoid()` - modifies the `src` attribute for in-line `<img>` tags. For example, the images in this file are `<img>` tags wrapped in `<p>` tags. *This is intentional, and aids in editing.*
    * `modifyInline()` - modifies the GitHub flavored images : `![an image](./path/to/image.jpg)`
    * Utilizes the `ModifyElements` class to effect changes to the image and link tags.

Here are the application features - 

* Configurable - The following items are configurable via JSON formatted files which can be chosen using a query when the page is loaded into a browser.
    * GitHub Access - Note that this application does **not** require a *Personal access token*.
        * Can be configured to retrieve the markdown file from a GitHub repository using the following options -
            * Repository Name
            * Owner
            * Branch
            * Markdown file - The file can be local or hosted on GitHub
    * Generates Static HTML - An optional setting controls this feature, the output file name is also configurable.
        * Open Graph Meta Tags - 
        * Meta Tags - Can be configured in the JSON file or can be retrieved from the GitHub repository.
            * Meta Description
            * Meta Keywords
* Modifies resource paths for images. The configurable GitHub settings are used in on-the-fly modification of image tags so that the `src` attributes point to the correct location.
* Modifies resource paths for in-line links such as `[test.md](./test.md)` and adds `target="_blank"` to the resulting HTML link. The configurable GitHub settings are used in on-the-fly modification of link tags so that the `href` attributes point to the correct location. **Note :** The code expects a *relative path* to the root of the repository.

# Implementation Overview

PHP was the primary technology used in this project. It provides all of the necessary capabilities needed to access and render the markdown content to HTML.

## Application Architecture

<p align="center">
  <img src="./mdimg/app-arch-simple-904x830.jpg" alt="Basic Architecture" txt="Basic Architecture" width="75%">
</p>

## Minimum Requirements

* PHP - Version 5.6 was used in development and testing. It was chosen because *standard hosting* was the targeted platform.
* Web Server - This project can be hosted on a internet accessible host. However for initial use and testing a local server such as [XAMPP](https://www.apachefriends.org/index.html) is recommended. This project was developed and tested on XAMPP - [xampp-win32-5.6.31-0-VC11-installer.exe](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/5.6.31/xampp-win32-5.6.31-0-VC11-installer.exe/download)
* Web Browser - My preferred development browser is *Chrome*.

### Extra

I used Netbeans 8.2 for the majority of my debugging. It works very well with PHP and Chrome.

# Running the Project

1. Download this repository as a zip file.
2. Unzip the contents to your hard drive.
3. Create a folder in `c:\xampp\htdocs` called `tests\mdrender`.
4. Copy the following into  `c:\xampp\htdocs\tests\mdrender` - 

* Folders & contents - 
    * `nbproject` - *not required if running on a hosting server*
    * `assets`
    * `mdimg`
* Files - 
    * `index.php`
    * `Parsedown.php`
    * `ParsedownModify.php`
    * `RenderConfig.php`
    * `github.json`
    * `test.json`
    * `test.md`

5. Run XAMPP and start Apache (*not necessary if running on a hosting server*)
6. Open your browser and navigate to - `http://[localhost | server]/tests/mdrender/index.php`
7. The page you see *should* look like this - 
 
<p align="center">
  <img src="./mdimg/mdrender-thumb-600x450.jpg" alt="Render Example" txt="Render Example" width="50%">
</p>

Here is the file in GitHub - [test.md](./test.md) (*right-click and open in a new tab or window*).

## Configuration

**github.json :** Typically it will not be necessary to edit this file. It contains *GitHub* specific configuration items that are not likely to change often.

```
{
    "reporaw"  : "https://raw.githubusercontent.com/",

    "repogit"  : "https://github.com/",

    "repoapi"  : "https://api.github.com/",
    "accheader": [
                    "application/vnd.github.v3+json",
                    "application/vnd.github.mercy-preview+json"
                 ],
}
```

It *should not be* necessary to edit the following in the `github.json` file - 
* `reporaw` - base URL for accessing *raw* GitHub files
* `repogit` - base URL for accessing GitHub 
* `repoapi` - base URL for accessing the GitHub API
* `accheader` - an array of two `Accept` headers, selected in code for specific API calls.

**test.json :** This file and its contents are specific to the Markdown file that you want to render. 

```
{
    "owner"    : "jxmot",
    "repo"     : "markdown-render",
    "branch"   : "master",

    "mdfilerem": true,
    "mdfile"   : "test.md",
    "mdpageopt": "./mdpageopt.json",

    "pagetitle": "markdown-render Test",

    "gitdesc"  : false,
    "metadesc" : "",

    "gittopics": false,
    "metakeyw" : "test,mdrender,markdown",

    "metaauth" : "https://github.com/jxmot",

    "genstatic": true,
    "statname" : "./test.html",

    "oghead": true,
    "ogjson": "./oghead-example-test.json"
}
```

The following found in `test.json` can be edited as needed - 
* `owner` - this is the owner of the repository where the markdown file to be rendered is residing.
* `repo` - the repository name that contains the markdown file
* `branch` - the branch that contains the markdown file
* `mdfilerem` - if **`true`** the application will obtain the markdown file *from* the repository, if it is **`false`** it will look for the file locally
* `mdfile` - the name of the targeted markdown file
* `mdpageopt` - the path + name of the configuration file which contains settings for page footer, social icons, and "to top" functionality
* `pagetitle` - this will become the text between the `<title>` tags in the rendered output
* `gitdesc` - if **`true`** the application will obtain the description from the specified repository, if **`false`** it will use the text found in `metadesc`
* `metadesc` - optional, used if `gitdesc` is `false`
* `gittopics` - **`true`** the application will obtain the topics found in the repository and place them in the meta keywords as a comma separated list, if **`false`** it will use the text found in `metakeyw`
* `metakeyw` - optional, used if `gittopics` is `false`
* `metaauth` - optional, fills in the meta author tag if there if it has text in it
* `genstatic` - if **`true`** the application will create a static HTML file from the rendered output.
* `statname` - the name of the generated static HTML file, since the rendered file will use the CSS and JS files it is best to save it in the current location (i.e. `./`)
* `oghead` - if **`true`** then meta tags containing *Open Graph* protocol data will be included within the `<head>` tags. **NOTE :** `genstatic` must be **`true`**, otherwise this field is ignored
* `ogjson` - the path + name of the configuration file which contains the data for the Open Graph meta tags

Additional JSON files can be created as needed and contain different repository information. To run the application using a different JSON file is accomplished using a *query*. For example if a JSON file named `myreadme.json` is to be used then point the browser to - `http://localhost/tests/mdrender/index.php?cfg=myreadme`.

**mdpageopt.json :** There are additional features that are configurable via a another JSON file  - 

```
{
    "footer": true,
    "footertxt": "&nbsp;2017 &copy; James Motyl&nbsp;",
    "socicon": true,
    "socitems": [
        { "url":"https://github.com/jxmot/", "class":"gh", "target":"_blank", "title":"See me on GitHub!" },
        { "url":"https://www.linkedin.com/in/jim-motyl/", "class":"in", "target":"_blank", "title":"See me on LinkedIn!" }
    ],
    "totop": true
}
```

* `footer` - If `true` a fixed position footer will be added to the page and the following items can also be configured. However if it is `false`, non existent, or if the file is missing then there will be no footer.
    * `footertxt` - text centered in the footer
    * `socicon` - if `true` then both social icons will be seen 
        * `socitems[0]` - used for the icon on the left side
        * `socitems[1]` - used for the icon on the right side
* `totop` -  "Go to Top" button, a simple "go to top" button that can be reused on any web page. It is implemented with the following - 
    * `assets/css/totop.css`
    * `assets/js/totop.js`
    * an HTML button located at the bottom of the document space - `<button id="gototop" class="gototop" onclick="jumpToTop()" title="Go to top of page">&#9650;<br>top</button>`

**oghead-example-test.json :** This file contains the required content for the "[Open Graph](http://ogp.me/)" protocol. I used it on pages shared with Twitter and LinkedIn. 

In `test.json` - 

```
{
# not related to other settings in this file


    "genstatic": true,
    "statname" : "./test.html",

    "oghead": true,
    "ogjson": "./oghead-example-test.json"
}
```

The Opeh Graph tags will not be rendered unless `genstatic` **and** `oghead` are true. The configuration for the meta tag content is in `oghead-example-test.json`. You can find the details in the **[oghead-example-test](oghead-example-test.md)** document.

## Additional Open Graph Information

The Open Graph options in this application are intended for use when creating a static page from the rendered ouput. Even if you want to continue live rendering of the page a static HTML would be necessary in order for the Open Graph parts to work correctly.

The Open Graph meta tags that are generated were intend for use on Facebook, LinkedIn, and Twitter. They have not been extensively tested elsewhere but are likely to work as expected.

Other things to know are - 

* In the `twitter:url` and `og:url` meta tags the `url` must end in `/` or reference an existing file.
* It seems that a larger *thumbnail* image works best. I've read conflicting info regarding the size of the image, and my choice for larger image is due to what I read in the [Facebook Best Practices](https://developers.facebook.com/docs/sharing/best-practices) docs.
* If problems occur try using one or more of these to find errors - 
    * [Facebook Object Debugger](https://developers.facebook.com/tools/debug/og/object/). You have to be logged into Facebook in order for the debugger to work.
    * [Twitter Card Validator](https://cards-dev.twitter.com/validator). You have to be logged into Twitter in order for the debugger to work.
    * [Social Debug](http://socialdebug.com/) - It "grades" your meta tags, seems to work pretty well.

### LinkedIn Notes

Sometimes there are issues when adding a link to LinkedIn's *media* or to posts where the image is incorrect. If that happens edit and place a small meaningless query at the end of the URL. This seems to force LinkedIn to read the Open Graph tags right away. An example URL - https://yoursite.com/**?1**. 

### Twitter Notes

The Twitter site and application do not appear to show the image right away. I *think* that the link target isn't scraped for the thumbnail until the post is viewed for the first time, not counting the original post. So if it doesn't show up right away quit the application or browser and restart.

## Other Modifiable Items

The bulk of the page styling is done with Bootstrap and a CSS file(`assets/css/document.css`). The coloring and some other style adjustments in that CSS file are tailored for use with the cyborg Bootstrap theme. 

# Development and Debugging

* Development Operating System - Windows 10 64bit

I used XAMPP and NetBeans 8.2(PHP) to develop and debug this project. In order to properly debug with the NetBeans IDE it is necessary to modify the XAMPP `php.ini` file. Contrary to the majority of on-line resources the *correct* settings are - 

```
[XDebug]
zend_extension = "./php_xdebug.dll"
; XAMPP and XAMPP Lite 1.7.0 and later come with a bundled xdebug at <XAMPP_HOME>/php/ext/php_xdebug.dll, without a version number.
xdebug.remote_enable=1
xdebug.remote_host=127.0.0.1
xdebug.remote_port=9000
; Port number must match debugger port number in NetBeans IDE Tools > Options > PHP
xdebug.remote_handler=dbgp
xdebug.profiler_enable=1
xdebug.profiler_output_dir="<XAMPP_HOME>\tmp"
```

Add the section above to your `php.ini` file. Under XAMPP it is located at `C:\xampp\php\php.ini`. In addition, this repository contains the NetBeans project settings files. They are located in `/nbproject`. After you have XAMPP and NetBeans installed it *should be* possible to open the project in Netbeans.

## Running under NetBeans

* Download and install XAMPP, make the modifcations describe above to the `php.ini` file.
* Download and install NetBeans, download the PHP/HTML5 flavor of NetBeans.
* Run NetBeans
    * Then File->Open Project and navigate to `c:\xampp\htdocs\tests\mdrender` and open the project

NetBeans will allow you to set breakpoints and examine variables.

## Running on a Host

Copy the files as described in [Running the Project](#running-the-project) to a folder on your server's *document root*. To run the test render navigate your browser to - `http[s]://yourserver/yourfolder/index.php`. The default configuration is in `test.json`. To run a different JSON configuration file create one with the appropriate modifications (*use* `test.json` *as a starting point*) and copy it to the folder on your server. Then you can navigate to - `http[s]://yourserver/yourfolder/index.php?cfg=yourconfig`.

# IMPORTANT Things to Note

The version of *Parsedown* used in this repository is realatively old. It was created *around* May 2017. I estimate that the version would have been 1.6.3, however the author(s) did not update the version number string in `Parsedown.php`.

There have been a large number of changes made at [Parsedown](https://github.com/erusev/parsedown) since the time when the current version was first obtained. ~~I plan on updating the local copy of Parsedown after activivity has settled down in the Parsedown repository~~. 

**UPDATE 2018-03-08 :** After much (very much) tinkering around with some "updated" version of parsedown I've decided that the version I'm using now will have to do. There were some changes that severly broke what I'm trying to do. Since the original code **is not commented sufficiently** it became increasing difficult to determine what exactly has changed and what the intent was. The problems were evident in embedded images, for example - `![some text](path/to/image.jpg)`. The 1.7.0 and 1.7.1 versions of Parsedown treated those *links* and processed them as such. During that process they're converted back to an embedded image. After I studied the Parsdown code in detail it appeared to me that the reason for that was a "fudge". Which was manifested as a call to inlineImage() and then from within that function to call inlineLink(). By calling inlineLink() the internals of the element array were manipulated to look like a `<a>` tag.

----

<p align="center">
  &copy; 2018 Jim Motyl
</p>
