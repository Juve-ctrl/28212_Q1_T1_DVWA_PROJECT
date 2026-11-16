
<?php

// Hardened File Inclusion handler.
// Validate the requested file and let the parent index.php include it.

unset($file);

// Preserve DVWA's initial redirect to include.php when no page is supplied.
if (!isset($_GET['page'])) {
    return;
}

// Only explicitly approved local PHP pages are permitted.
$allowedFiles = [
    'include.php',
    'file1.php',
    'file2.php',
    'file3.php',
];

// Accept only a non-empty scalar filename.
$requestedFile = $_GET['page'];

if (!is_string($requestedFile) || $requestedFile === '') {
    http_response_code(400);
    echo 'ERROR: Invalid file request.';
    exit;
}

// Reject path separators and anything outside the strict allowlist.
if (
    str_contains($requestedFile, '/') ||
    str_contains($requestedFile, '\\') ||
    str_contains($requestedFile, "\0") ||
    !in_array($requestedFile, $allowedFiles, true)
) {
    http_response_code(403);
    echo 'ERROR: File not permitted.';
    exit;
}

// Resolve the approved file inside the fixed FI directory.
$baseDirectory = realpath(dirname(__DIR__));

if ($baseDirectory === false) {
    http_response_code(500);
    echo 'ERROR: File service unavailable.';
    exit;
}

$resolvedFile = realpath(
    $baseDirectory . DIRECTORY_SEPARATOR . $requestedFile
);

// Require the resolved file to be a direct child of the approved directory.
if (
    $resolvedFile === false ||
    dirname($resolvedFile) !== $baseDirectory ||
    !is_file($resolvedFile) ||
    !is_readable($resolvedFile)
) {
    http_response_code(404);
    echo 'ERROR: File not found.';
    exit;
}

// Pass the verified absolute path to the parent index.php.
// The parent script performs the actual include.
$file = $resolvedFile;

