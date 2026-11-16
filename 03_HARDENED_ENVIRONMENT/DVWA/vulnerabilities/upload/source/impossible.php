<?php

// Hardened DVWA File Upload handler.
// Accept only genuine, validated JPEG and PNG uploads.

if (isset($_POST['Upload'])) {

    checkToken(
        $_REQUEST['user_token'] ?? '',
        $_SESSION['session_token'] ?? '',
        'index.php'
    );

    $temporaryOutput = null;
    $image = null;

    try {

        if (
            !isset($_FILES['uploaded']) ||
            !is_array($_FILES['uploaded'])
        ) {
            throw new RuntimeException('No valid upload was received.');
        }

        $upload = $_FILES['uploaded'];

        if (
            !isset(
                $upload['name'],
                $upload['tmp_name'],
                $upload['error'],
                $upload['size']
            ) ||
            !is_string($upload['name']) ||
            !is_string($upload['tmp_name']) ||
            !is_int($upload['error']) ||
            !is_int($upload['size'])
        ) {
            throw new RuntimeException('Invalid upload information.');
        }

        if ($upload['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('The upload did not complete successfully.');
        }

        if (
            !is_uploaded_file($upload['tmp_name']) ||
            !is_file($upload['tmp_name'])
        ) {
            throw new RuntimeException('The uploaded file could not be verified.');
        }

        $actualSize = filesize($upload['tmp_name']);

        if (
            $actualSize === false ||
            $actualSize < 1 ||
            $actualSize > 100000 ||
            $upload['size'] !== $actualSize
        ) {
            throw new RuntimeException('The image exceeds the permitted size.');
        }

        $extension = strtolower(
            pathinfo($upload['name'], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            throw new RuntimeException('Only JPEG and PNG images are accepted.');
        }

        // Determine MIME type from the actual file contents.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if ($finfo === false) {
            throw new RuntimeException('File validation is unavailable.');
        }

        $detectedMime = finfo_file($finfo, $upload['tmp_name']);

        // Independently inspect the image structure.
        $imageInfo = @getimagesize($upload['tmp_name']);

        if ($imageInfo === false) {
            throw new RuntimeException('The uploaded file is not a valid image.');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $imageType = $imageInfo[2];

        if (
            $width < 1 ||
            $height < 1 ||
            $width > 20000 ||
            $height > 20000 ||
            ($width * $height) > 20000000
        ) {
            throw new RuntimeException('The image dimensions are not permitted.');
        }

        $isJpeg =
            $detectedMime === 'image/jpeg' &&
            $imageInfo['mime'] === 'image/jpeg' &&
            $imageType === IMAGETYPE_JPEG &&
            in_array($extension, ['jpg', 'jpeg'], true);

        $isPng =
            $detectedMime === 'image/png' &&
            $imageInfo['mime'] === 'image/png' &&
            $imageType === IMAGETYPE_PNG &&
            $extension === 'png';

        if (!$isJpeg && !$isPng) {
            throw new RuntimeException('The file format is not permitted.');
        }

        // Decode the image using the appropriate GD decoder.
        $image = $isJpeg
            ? @imagecreatefromjpeg($upload['tmp_name'])
            : @imagecreatefrompng($upload['tmp_name']);

        if ($image === false) {
            throw new RuntimeException('The image could not be decoded.');
        }

        // Use the application-defined upload directory.
        $uploadDirectory = realpath($PHPUploadPath);

        if (
            $uploadDirectory === false ||
            !is_dir($uploadDirectory) ||
            !is_writable($uploadDirectory)
        ) {
            throw new RuntimeException('The upload directory is unavailable.');
        }

        // Generate a random server-side filename.
        $newExtension = $isJpeg ? 'jpg' : 'png';
        $filename = bin2hex(random_bytes(16)) . '.' . $newExtension;

        $destination = $uploadDirectory .
            DIRECTORY_SEPARATOR . $filename;

        // Create the temporary output inside the destination directory.
        $temporaryOutput = tempnam($uploadDirectory, '.dvwa-upload-');

        if ($temporaryOutput === false) {
            throw new RuntimeException('A temporary image could not be created.');
        }

        // Re-encode the image to remove original metadata.
        $saved = $isJpeg
            ? @imagejpeg($image, $temporaryOutput, 90)
            : @imagepng($image, $temporaryOutput, 9);

        unset($image);
        $image = null;

        if (!$saved || !is_file($temporaryOutput) ||
            filesize($temporaryOutput) === 0) {
            throw new RuntimeException('The image could not be re-encoded.');
        }

        if (!chmod($temporaryOutput, 0644)) {
            throw new RuntimeException('The image permissions could not be set.');
        }

        // Atomically move the re-encoded image into its final location.
        if (!rename($temporaryOutput, $destination)) {
            throw new RuntimeException('The image could not be saved.');
        }

        $temporaryOutput = null;

        $relativePath = 'hackable/uploads/' . $filename;

        $html .= '<pre><a href="' .
            htmlspecialchars($relativePath, ENT_QUOTES, 'UTF-8') .
            '">' .
            htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') .
            '</a> successfully uploaded!</pre>';

    } catch (Throwable $exception) {

        error_log('DVWA hardened upload rejected: ' . $exception->getMessage());

        $html .= '<pre>Your image was not uploaded. Only valid JPEG or PNG images within the permitted limits are accepted.</pre>';

    } finally {

        if ($image !== null) {
            unset($image);
        }

        if (
            $temporaryOutput !== null &&
            is_file($temporaryOutput)
        ) {
            unlink($temporaryOutput);
        }
    }
}

// Generate a fresh anti-CSRF token.
generateSessionToken();


