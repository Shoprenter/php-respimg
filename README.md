# php-respimg

A responsive image workflow for optimizing and resizing your images.

See also [grunt-respimg](https://www.npmjs.com/package/grunt-respimg).

Full documentation available at <https://rawgit.com/nwtn/php-respimg/master/docs/index.html>.

## Examples

To resize one raster image, without optimization:

```php
$image = new nwtn\Respimg($input_filename);
$image->smartResize($output_width, $output_height, false);
$image->writeImage($output_filename);
```

To resize one raster image and maintain aspect ratio, without optimization:

```php
$image = new nwtn\Respimg($input_filename);
$image->smartResize($output_width, 0, false);
$image->writeImage($output_filename);
```

To resize one raster image and maintain aspect ratio, with optimization:

```php
$image = new nwtn\Respimg($input_filename);
$image->smartResize($output_width, 0, true);
$image->writeImage($output_filename);
nwtn\Respimg::optimize($output_filename, 0, 1, 1, 1);
```

To resize a directory of raster images and maintain aspect ratio, with optimization:

```php
$exts = array('jpeg', 'jpg', 'png');
if ($dir = opendir($input_path)) {
	while (($file = readdir($dir)) !== false) {
		$base = pathinfo($file, PATHINFO_BASENAME);
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if (in_array($ext, $exts)) {
			$image = new nwtn\Respimg($input_path . '/' . $file);
			$image->smartResize($width, 0, true);
			$image->writeImage($output_path . '/' . $base . '-w' . $w . '.' . $ext);
		}
	}
}
nwtn\Respimg::optimize($output_path, 0, 1, 1, 1);
```

To resize a directory of raster images and SVGs and maintain aspect ratio, with optimization:

```php
$exts = array('jpeg', 'jpg', 'png');
if ($dir = opendir($input_path)) {
	while (($file = readdir($dir)) !== false) {
		$base = pathinfo($file, PATHINFO_BASENAME);
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if (in_array($ext, $exts)) {
			$image = new nwtn\Respimg($input_path . '/' . $file);
			$image->smartResize($width, 0, true);
			$image->writeImage($output_path . '/' . $base . '-w' . $w . '.' . $ext);
		} elseif ($ext === 'svg') {
			copy($input_path . '/' . $file, $output_path . '/' . $file);
			nwtn\Respimg::rasterize($input_path . '/' . $file, $output_path . '/', $width, 0);
		}
	}
}
nwtn\Respimg::optimize($output_path, 3, 1, 1, 1);
```

## Release History

### 1.0.1

* Library loading bug fix
* Namespacing in README

### 1.0.0

* Major refactoring
* Image optimization
* SVG rasterization
* Basic (non-unit) tests

### 0.0.2

* Fix a path in the test file
* Minor colorspace change (should have no effect on output)
* Comments and stuff

### 0.0.1

* Packagist release

### 0.0.0

* Super experimental pre-release. Feel free to mess about with it, but don’t expect much.