When contributing to this repository, please first discuss the change you wish to make via issue with the owners of this repository before making a change.

## Reporting bugs

Please report bugs you find via GitHub issues.

A bug report should consist of:

* A descriptive title
* Steps to reproduce the problem (what you clicked, tapped, typed, saw and heard)
* What happened ("actual results")
* How this differed from what you expected to happen ("expected results")

When relevant, include configuration information (such as "Chrome 85 on macOS"), URLs and screenshots.

## Getting started

1. Clone the repository from GitHub
1. Install [Composer](https://getcomposer.org/download/)
1. Install [Node and NPM](https://nodejs.org/en/)
1. Run `composer install`
1. Run `npm install`
1. Run `npm install -g grunt` (you may need to use `sudo`)

## Tools

This project uses Grunt, SASS, ESLint, stylelint and PHPCS. PHP modules are installed using Composer. JavaScript modules are installed using node.

## Local development

We use [VVV](https://varyingvagrantvagrants.org/) for local development. This is not a requirement, but you may find it helpful.

The [Query Monitor](https://wordpress.org/plugins/query-monitor/) plugin is recommended in development and staging environments.

Ensure your hostname ends with `.test`, such as `elections.test`.

## Branching

* Create feature and bugfix branches off of `master`.
* Name branches `fix/xyz` or `feature/abc`, depending on the type of work being done.
* Test branches locally, and then merge into `develop` to test on staging.
* Open a pull request to merge your branch in to `master`.

Be sure to run `phpcs` and fix any warnings before committing.

## Commits

Use descriptive commit messages.

## Pull requests

* Give your pull request a descriptive title.
* Tag related issues (i.e. `Fixes #14`).

## Using Grunt

Run `grunt watch` before starting development. It will check your source files for errors, automatically fix many of them, and compile assets for you.

A precommit task will lint your PHP files.

### Assets (CSS, JavaScript and images)

Modify files in `assets/css/src` and `assets/js/src`. If you're not running `grunt watch`, you can lint and compile them manually with `grunt css`, `grunt js` or `grunt css-js`.

### SVGs

SVG files are not automatically minified. When you add a or modify and SVG file, run `grunt svg` _twice_.

### README

Modify `readme.txt`. After making changes, run `grunt readme` to update the markdown version (`readme.md`).

