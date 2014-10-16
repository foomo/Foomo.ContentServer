<?php

/*
 * This file is part of the foomo Opensource Framework.
 *
 * The foomo Opensource Framework is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public License as
 * published  by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * The foomo Opensource Framework is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * the foomo Opensource Framework. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Foomo\ContentServer;

/**
 * @link    www.foomo.org
 * @license www.gnu.org/licenses/lgpl.txt
 */
class MimeType
{
	// --------------------------------------------------------------------------------------------
	// ~ Constants
	// --------------------------------------------------------------------------------------------

	const MIME_RADACT_RAD_DOC = 'application/x-radactRaddoc5';
	const MIME_RADACT_TEXT    = 'application/x-radactText';
	const MIME_RADACT_LINKER  = 'application/x-radactLinker';
	const MIME_RADACT_APP     = 'application/x-radactApp';
	const MIME_RADACT_FOLDER  = 'application/x-radactFolder';
	const MIME_RADACT_LINK    = 'application/x-radactLink';

	const MIME_OCTET_STREAM = 'application/octet-stream';
	const MIME_ZIP          = 'application/x-zip';
	const MIME_PDF          = 'application/pdf';
	const MIME_FLASH        = 'application/x-shockwave-flash';

	const MIME_IMAGE_GIF  = 'image/gif';
	const MIME_IMAGE_JPEG = 'image/jpeg';
	const MIME_IMAGE_PNG  = 'image/png';

	const MIME_TEXT_HTML   = 'text/html';
	const MIME_TEXT_PLAIN  = 'text/plain';
	const MIME_TEXT_XML    = 'text/xml';
	const MIME_FLASH_VIDEO = 'video/x-flv';
}
