<?php

	# php-respimg
	# https://github.com/nwtn/php-respimg
	#
	# © David Newton
	# david@davidnewton.ca
	#
	# For the full license information, view the LICENSE file that was distributed
	# with this source code.


	class Respimg extends Imagick {
		public function betterResize($columns, $rows, $optim) {
			$this->setOption('filter:support', '2.0');

			// there is no way to choose a "Triangle" filter with "thumbnail" via Imagick
			// so, recreate the IM thumbnail function here
			// https://github.com/mkoppanen/imagick/issues/90
			$SampleFactor = 5;
			$filter = imagick::FILTER_TRIANGLE;
			$x_factor = (double)$columns / (double)$this->getImageWidth();
			$y_factor = (double)$rows / (double)$this->getImageHeight();
			if ($rows == 0) {
				$rows = round(($x_factor * (double)$this->getImageHeight()));
			}
			if (($x_factor * $y_factor) > 0.1) {
				$this->resizeImage($columns, $rows, $filter, 1);
			} else {
				if ((($SampleFactor * $columns) < 128) || (($SampleFactor * $rows) < 128)) {
					$this->resizeImage($columns, $rows, $filter, 1);
				} else {
					$this->sampleImage($SampleFactor * $columns, $SampleFactor * $rows);
					$this->resizeImage($columns, $rows, $filter, 1);
				}
			}
			if ($this->getImageAlphaChannel() == imagick::ALPHACHANNEL_UNDEFINED) {
				$this->setImageAlphaChannel(imagick::ALPHACHANNEL_OPAQUE);
			}
			$this->setImageDepth(8);
			$this->setInterlaceScheme(imagick::INTERLACE_NO);
			foreach ($this->getImageProfiles('*', true) as $key => $value) {
				if ($key != 'icc' && $key != 'icm') {
					$this->removeImageProfile($key);
				}
			}
			if (method_exists($this, 'deleteImageProperty')) {
				$this->deleteImageProperty('comment');
				$this->deleteImageProperty('Thumb::URI');
				$this->deleteImageProperty('Thumb::MTime');
				$this->deleteImageProperty('Thumb::Size');
				$this->deleteImageProperty('Thumb::Mimetype');
				$this->deleteImageProperty('software');
				$this->deleteImageProperty('Thumb::Image::Width');
				$this->deleteImageProperty('Thumb::Image::Height');
				$this->deleteImageProperty('Thumb::Document::Pages');
			} else {
				$this->setImageProperty('comment', '');
				$this->setImageProperty('Thumb::URI', '');
				$this->setImageProperty('Thumb::MTime', '');
				$this->setImageProperty('Thumb::Size', '');
				$this->setImageProperty('Thumb::Mimetype', '');
				$this->setImageProperty('software', '');
				$this->setImageProperty('Thumb::Image::Width', '');
				$this->setImageProperty('Thumb::Image::Height', '');
				$this->setImageProperty('Thumb::Document::Pages', '');
			}
			// end fake thumbnail function

			if ($optim) {
				$this->unsharpMaskImage(0.25, 0.08, 8.3, 0.045);
			} else {
				$this->unsharpMaskImage(0.25, 0.25, 8, 0.065);
			}
			$this->posterizeImage(136, false);
			$this->setImageCompressionQuality(82);
			$this->setOption('jpeg:fancy-upsampling', 'off');
			$this->setOption('png:compression-filter', '5');
			$this->setOption('png:compression-level', '9');
			$this->setOption('png:compression-strategy', '1');
			$this->setOption('png:exclude-chunk', 'all');
			$this->setInterlaceScheme(imagick::INTERLACE_NO);

			$this->setColorspace(imagick::COLORSPACE_SRGB);

			if (!$optim) {
				$this->stripImage();
			}
		}
	}

?>
