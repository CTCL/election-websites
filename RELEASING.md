Here’s how to release a new version of the election websites theme.

## Version numbering

The election website template should use [Semantic Versioning](https://semver.org/).

## Update the version number

Update the following files with the new version number:

* `style.css`
* `functions.php`
* `package.json`
* `package-lock.json`

## Update the changelog

Update the changelog in `README.md`. Describe the changes in a few bullet points. Use language understandable by election officials, not software engineers.

## Update .gitattributes

If you have added configuration or intermediate build files that should not be included in the release, add them to `.gitattributes`.

## Update the master branch

Ensure all changes are pushed and merged to `master`.

## Create a release on GitHub

* [Draft a new release](https://github.com/CTCL/election-websites/releases/new)
* Enter the version number into tag version (i.e. `1.0.2`). Do _not_ include `v` in the tag name, i.e. _not_ `v1.0.1`.
* Copy and paste the bullet points from the changelog in the “Describe this release” field.
* Click `Publish release`

## Verify the release

Check the values returned from the [GitHub release API](https://api.github.com/repos/CTCL/election-websites/releases/latest) and confirm the version number is correct.
