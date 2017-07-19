# Contributing to YouTube-Downloader

First off, thanks for taking the time to contribute!

The following is a set of guidelines for contributing to this project. These are mostly guidelines, not rules. Use your best judgment, and feel free to propose changes to this document in a pull request.

#### Table Of Contents

[I don't want to read this whole thing, I just have a question!!!](#i-dont-want-to-read-this-whole-thing-i-just-have-a-question)

[What should I know before I get started?](#what-should-i-know-before-i-get-started)
  * [Goals](#goals)
  * [Semantic versioning](#semantic-versioning)

[How Can I Contribute?](#how-can-i-contribute)
  * [Reporting Bugs](#reporting-bugs)
  * [Suggesting Enhancements](#suggesting-enhancements)
  * [Your First Code Contribution](#your-first-code-contribution)
  * [Pull Requests](#pull-requests)

[Styleguides](#styleguides)
  * [PHP Styleguide](#php-styleguide)
  * [HTML/CSS Styleguide](#htmlcss-styleguide)

## I don't want to read this whole thing I just have a question!!!

> **Note:** Please don't file an issue to ask a question. [We have an official Gitter chat](https://gitter.im/jeckman-YouTube-Downloader/Lobby), where the community can help you if you have questions.

## What should I know before I get started?

### Goals

We have some goals we want to achieve with this project:

- Create a library that delivers data and download links for youtube videos.
- Create a UI for downloading the videos.
- Have no external dependencies to other services.
- Have no external dependencies to other libraries in production.
- Installation should be foolproof (unzip on server and go)

### Semantic versioning

This project contains two parts:

1. A library that delivers data and download links for youtube videos.
2. A Web interface that uses this library

The library should be installable via Composer so it can be used in other PHP project. It follows [semantic versioning 2](http://semver.org/spec/v2.0.0.html).

The web interface must be accessible using the `index.php` in the root folder. All other links and files could be changed.

## How Can I Contribute?

### Reporting Bugs

This section guides you through submitting a bug report. Following these guidelines helps maintainers and the community understand your report :pencil:, reproduce the behavior :computer: :computer:, and find related reports :mag_right:.

Before creating bug reports, please check [this list](#before-submitting-a-bug-report) as you might find out that you don't need to create one. When you are creating a bug report, please [include as many details as possible](#how-do-i-submit-a-good-bug-report). Fill out [the required template](.github/ISSUE_TEMPLATE.md), the information it asks for helps us resolve issues faster.

> **Note:** If you find a **Closed** issue that seems like it is the same thing that you're experiencing, open a new issue and include a link to the original issue in the body of your new one.

#### Before Submitting A Bug Report

* **Perform a [search](https://github.com/jeckman/YouTube-Downloader/issues?utf8=%E2%9C%93&q=is%3Aissue)** to see if the problem has already been reported. If it has **and the issue is still open**, add a comment to the existing issue instead of opening a new one.

#### How Do I Submit A (Good) Bug Report?

Bugs are tracked as [GitHub issues](https://guides.github.com/features/issues/). When creating an issue please provide the following information by filling in [the template](.github/ISSUE_TEMPLATE.md).

Every server is different and every config change has effects on the behavior of YouTube-Downloader. Explain the problem and include additional details to help maintainers reproduce the problem:

* **Use a clear and descriptive title** for the issue to identify the problem.
* **Describe the exact steps which reproduce the problem** in as many details as possible. For example, start by explaining how you are using YouTube-Downloader (web or library). When listing steps, **don't just say what you did, but explain how you did it**. For example, what url have you entered and how do you click on a link to download a viedeo.
* **Provide specific examples to demonstrate the steps**. If possible include a link to your live installation so we can take a look or try it ourself.
* **If you're providing code snippets in the issue**, use [Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).
* **Describe the behavior you observed after following the steps** and point out what exactly is the problem with that behavior.
* **Explain which behavior you expected to see instead and why.**
* **Include screenshots and animated GIFs** which show you following the described steps and clearly demonstrate the problem. You can use [this tool](http://www.cockos.com/licecap/) to record GIFs on macOS and Windows, and [this tool](https://github.com/colinkeenan/silentcast) or [this tool](https://github.com/GNOME/byzanz) on Linux.

Include details about your configuration and environment:

* **Which version of YouTube-Downloader are you running?** You can get this information from the footer in the web interface.
* **What's the version of PHP you're using**?
* **Have you modified the configuration** in `config/custom.php`? If so, provide the contents of this file, preferably in a [code block](https://help.github.com/articles/markdown-basics/#multiple-lines) or with a link to a [gist](https://gist.github.com/).

### Suggesting Enhancements

This section guides you through submitting an enhancement suggestion for YouTube-Downloader, including completely new features and minor improvements to existing functionality. Following these guidelines helps maintainers and the community understand your suggestion :pencil: and find related suggestions :mag_right:.

Before creating enhancement suggestions, please check [this list](#before-submitting-an-enhancement-suggestion) as you might find out that you don't need to create one. When you are creating an enhancement suggestion, please [include as many details as possible](#how-do-i-submit-a-good-enhancement-suggestion). Fill in [the template](.github/ISSUE_TEMPLATE.md), including the steps that you imagine you would take if the feature you're requesting existed.

#### Before Submitting An Enhancement Suggestion

* **Perform a [search](https://github.com/jeckman/YouTube-Downloader/issues?utf8=%E2%9C%93&q=)** to see if the enhancement has already been suggested. If it has, add a comment to the existing issue instead of opening a new one.

#### How Do I Submit A (Good) Enhancement Suggestion?

Enhancement suggestions are tracked as [GitHub issues](https://guides.github.com/features/issues/), so please create an issue and provide the following information:

* **Use a clear and descriptive title** for the issue to identify the suggestion.
* **Provide a step-by-step description of the suggested enhancement** in as many details as possible.
* **Provide specific examples to demonstrate the steps**. Include copy/pasteable snippets which you use in those examples, as [Markdown code blocks](https://help.github.com/articles/markdown-basics/#multiple-lines).
* **Describe the current behavior** and **explain which behavior you expected to see instead** and why.
* **Include screenshots and animated GIFs** which help you demonstrate the steps or point out the part of YouTube-Downloader which the suggestion is related to. You can use [this tool](http://www.cockos.com/licecap/) to record GIFs on macOS and Windows, and [this tool](https://github.com/colinkeenan/silentcast) or [this tool](https://github.com/GNOME/byzanz) on Linux.
* **Explain why this enhancement would be useful** to most YouTube-Downloader users.
* **List some other projects, libraries or applications where this enhancement exists.**
* **Which version of YouTube-Downloader are you running?** You can get this information from the footer in the web interface.
* **What's the version of PHP you're using**?

### Your First Code Contribution

Unsure where to begin contributing to YouTube-Downloader? You can start by looking through the [open `enhancement` issues](https://github.com/jeckman/YouTube-Downloader/issues?q=is%3Aissue+is%3Aopen+label%3Aenhancement).

Please note that you contributing to an open source project. By contributing to this project:

- you put your code under the [GPL2 license](https://github.com/jeckman/YouTube-Downloader/blob/master/LICENSE)
- you assure that you have the permission to put your code under the [GPL2 license](https://github.com/jeckman/YouTube-Downloader/blob/master/LICENSE)

#### Local development

YouTube-Downloader can be developed locally, by checking out the repository and run the PHP built-in server. The requirements are:

* PHP >=5.4
* [Composer](https://getcomposer.org)

```
$ git clone url-to-your-git-repository
$ cd path-to-project/
$ composer update
$ vendor/bin/phpunit
$ php -S localhost:8080
```

By running `composer update` all dependencies for development are installed by composer. Note that you also can download the `composer.phar` to the project folder and run `$ php composer.phar update`.

By running `vendor/bin/phpunit` all tests are being performed. You should run the tests at least once before submitting a pull request.

By running `php -S localhost:8080` PHP starts the built-in server and you can use YouTube-Downloader on your machine while call http://localhost:8080 in your browser.

### Pull Requests

* Fill in [the required template](.github/PULL_REQUEST_TEMPLATE.md)
* Do not include issue numbers in the PR title
* Include screenshots and animated GIFs in your pull request whenever possible.
* Follow the [PHP](#php-styleguide) and [HTML/CSS](#htmlcss-styleguide) styleguides.
* Include unit tests in the `./tests` folder. Run them using `vendor/bin/phpunit`.
* End all files with a newline
* Avoid platform-dependent code
* Place UI changes in the following folder:
    * HTML should be only in `./templates/`
    * CSS should be only in `./css`
* PHP classes must follow [PSR-4 autoloading](http://www.php-fig.org/psr/psr-4/) and must be in the namespace `YoutubeDownloader`.

## Styleguides

### PHP Styleguide

All PHP files muss adhere to [PSR-2](http://www.php-fig.org/psr/psr-2/) with these exceptions:

* Code MUST use tabs for indenting, not 4 spaces.

### HTML/CSS Styleguide

* Code MUST use tabs for indenting, not 4 spaces.
