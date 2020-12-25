# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/pop-schema/taxonomyquery-wp).


## Pull Requests

- **[PSR-12 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md)** - Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Run static analysis** - Potential bugs must be detected and fixed before they happen.

- **Use supported PHP features only** - The list of what PHP features are supported is described in `README.md`, use only those!

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.


## Running Coding Standards

``` bash
$ composer check-style
$ composer fix-style
```


## Running Tests

``` bash
composer test
```


## Running Static Analysis

``` bash
composer analyse
```


## Dry-running PHP-version Downgrades

``` bash
composer preview-code-downgrade
```


**Happy coding**!
