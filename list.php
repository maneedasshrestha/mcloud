<?php
session_start();
require('functions.php');

// Check if user is logged in, redirect to login page if not
loggedin_only();

// Handle file upload if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    upload();
}

// Handle logout if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}

// Determine upload directory based on session or default to 'uploads/'
$upload_dir = $_SESSION['upload_dir'] ?? 'uploads';

// Find all files inside the determined uploads directory and list them
$files = array_diff(scandir($upload_dir), array('.', '..'));

// Sort files by modification time
function sortFilesByTime($a, $b) {
    global $upload_dir;
    return filemtime($upload_dir . '/' . $b) - filemtime($upload_dir . '/' . $a);
}
usort($files, 'sortFilesByTime');

// Function to check if a file is an image
function isImage($filePath) {
    $mime = mime_content_type($filePath);
    return strpos($mime, 'image') === 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-Cloud</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .bg-royalblue {
            background-color: royalblue;
        }

        @media screen and (max-width: 768px) {
            .smol-screen-no-show {
                display: none;
            }

            .out-box {
                width: auto !important;
            }

            .smol-screen-outer-box {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                padding-top: 2rem !important;
            }

            .limited-text {
                position: relative;
                display: inline-block;
                white-space: nowrap;
                overflow: hidden;
            }

            .limited-text::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 3ch;
                /* Adjust based on the fade effect length */
                height: 100%;
                background: linear-gradient(to left, white, transparent);
                pointer-events: none;
            }

            .limited-text span {
                display: inline-block;
                max-width: 8ch;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Custom styles */
            .round-button {
                display: block !important;
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                background-color: #000C66;
                border-radius: 50%;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                display: flex;
                justify-content: center;
                align-items: center;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Style for the plus symbol */
            .smol-screen-button-disappear {
                display: none;
            }
        }

        .plus-symbol {
            position: relative;
            width: 20px;
            height: 2px;
            font-weight: 900;
            background-color: white;
        }

        .plus-symbol::before,
        .plus-symbol::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 2px;
            background-color: white;
        }

        .plus-symbol::before {
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.png">
</head>

<body>
    <div class="container out-box">
        <nav class="navbar justify-content-between" style="margin-top: 2rem;">
            <a class="navbar-brand" style="color:#000C66; font-weight: 650; font-size: 27;">M-Cloud</a>
            <div>
                <form action="#" method="POST">
                    <button type="submit" name="upload" class="btn smol-screen-button-disappear" style="background-color: #000C66; color: white; border-radius: 10px; margin-bottom: 0; margin-right: 1rem;">Upload</button>
                    <button type="submit" name="logout" class="btn" style="background-color: #000C66; color: white; border-radius: 10px; margin-bottom: 0;">Log Out</button>
                </form>
            </div>
        </nav>
        <form action="#" method="POST">
            <button type="submit" name="upload" class="round-button" style="display: none;">
                <span class="plus-symbol"></span>
            </button>
        </form>
        <div class="bg-white text-dark p-4 smol-screen-outer-box">
            <div class=" bg-white rounded-lg shadow-lg overflow-hidden" style="background-color: #000C66;">
                <div class="p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h5 font-weight-bold text-dark">Welcome Back, <?php echo $_SESSION['username'] ?? 'User'; ?>!</h1>
                            <p>If Nikas, click <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">this.</a> :D</p>
                        </div>
                    </div>
                    <input type="text" id="searchBar" class="form-control mb-4" placeholder="Search files...">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="smol-screen-no-show">Uploaded On</th>
                                    <th class="smol-screen-no-show">Size</th>
                                    <th>Preview</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody id="fileList">
                                <?php foreach ($files as $file): ?>
                                    <tr class="file-item" data-file-name="<?php echo $file; ?>">
                                        <td class="d-flex align-items-center flex-wrap">
                                            <div class="text-dark limited-text">
                                                <?php if (isImage($upload_dir . '/' . $file)): ?>
                                                    <img src="<?php echo $upload_dir . '/' . $file; ?>" class="thumbnail mr-2">
                                                <?php else: ?>
                                                    <i class="fa fa-file-o thumbnail mr-2" style="font-size:24px;"></i>
                                                <?php endif; ?>
                                                <span><?php echo $file; ?></span>
                                            </div>
                                        </td>
                                        <td class="smol-screen-no-show">
                                            <?php echo date("M d, Y", filemtime($upload_dir . '/' . $file)); ?>
                                        </td>
                                        <td class="smol-screen-no-show">
                                            <?php echo round(filesize($upload_dir . '/' . $file) / (1024 * 1024), 2) . " MB"; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $upload_dir . '/' . $file; ?>" target="_blank" class="btn" style="color: #000C66;">
                                                <i class="fa fa-eye" style="font-size:24px;"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo $upload_dir . '/' . $file; ?>" download class="btn" style="color: #000C66;">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchBar = document.getElementById('searchBar');
            var fileList = document.getElementById('fileList');
            searchBar.addEventListener('input', function(e) {
                var query = e.target.value.toLowerCase();
                var fileItems = fileList.getElementsByClassName('file-item');
                Array.from(fileItems).forEach(function(item) {
                    var fileName = item.getAttribute('data-file-name').toLowerCase();
                    if (fileName.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
