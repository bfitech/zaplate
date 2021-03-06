
zaplate
=======

PHP is an HTML template engine. This only sugars it up a bit, enough
to render mostly-static HTML, which is all `zap*` ever needs due to
it being RESTful-oriented.

Use other full-blown templating engines for rendering complex dynamic HTML.

[![Latest Stable Version](https://poser.pugx.org/bfitech/zaplate/v/stable)](https://packagist.org/packages/bfitech/zaplate)
[![Latest Unstable Version](https://poser.pugx.org/bfitech/zaplate/v/unstable)](https://packagist.org/packages/bfitech/zaplate)
[![Build Status](https://travis-ci.org/bfitech/zaplate.svg?branch=master)](https://travis-ci.org/bfitech/zaplate)
[![Codecov](https://codecov.io/gh/bfitech/zaplate/branch/master/graph/badge.svg)](https://codecov.io/gh/bfitech/zaplate)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/bfitech/zaplate/master/LICENSE)

----

## Installation

Install it from Packagist:

```bash
$ composer -vvv require bfitech/zaplate
```

## Sample Usage

### template.php

```php
<p><?php echo $group ?></p>
<ul>
	<?php foreach ($members as $member): ?>
	<li><?php echo $member ?></li>
	<?php endforeach; ?>
</ul>
```

### renderer.php

```php
<?php

require __DIR__ . '/vendor/autoload.php';

class Filter {
	public function whoami($name) {
		if (is_string($name))
			return $name;
		return array_map(function($iname){
			if (stripos($iname, 'jekyll') !== false)
				return 'Mr Hyde';
			return $iname;
		}, $name);
	}
}

BFITech\ZapTemplate\Template::load('template.php', [
	'group' => "Extraordinary Gents",
	'members' => [
		'Allan Quatermain',
		'Henry Jekyll',
	],
], [
	[(new Filter), 'whoami'],
]);
```

Run it:

```txt
$ php renderer.php
<p>Extraordinary Gents</p>
<ul>
	<li>Allan Quatermain</li>
	<li>Mr Hyde</li>
</ul>
```

**PROTIP:** If you want to minify the HTML on production, run your
template file through ~~your~~my favorite
[minifier](https://github.com/kangax/html-minifier) prior to
rendering it, e.g.:

```txt
$ [ ! -f template.orig.php ] && cp template.{,orig.}php
$ php -w template.orig.php | \
> html-minifier \
>   --collapse-whitespace \
>   --trim-custom-fragments > \
> template.php
```

Aaand ... huzzah!

```txt
$ php renderer.php
<p>Extraordinary Gents</p><ul><li>Allan Quatermain</li><li>Mr Hyde</li></ul>
```

## Documentation

Documentation available with:

```txt
$ doxygen
$ x-www-browser docs/html/index.html
```

