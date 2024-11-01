<?php
session_start();

// Declare max file upload size limit
$maxFileSize = 50000000;
ini_set('upload_max_filesize', $maxFileSize);

// Check if session is active
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $buttonText = "Dashboard";
    $redirectUrl = "list.php";
} else {
    $buttonText = "Log In";
    $redirectUrl = "login.php";
}

// Determine upload directory based on session variable
$upload_dir = $_SESSION['upload_dir'] ?? 'uploads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    foreach ($_FILES['files']['name'] as $key => $fileName) {
        $fileTmpName = $_FILES['files']['tmp_name'][$key];
        $fileSize = $_FILES['files']['size'][$key];
        $fileError = $_FILES['files']['error'][$key];

        if ($fileError === 0) {
            if ($fileSize < $maxFileSize) {
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                if (file_exists("$upload_dir/$fileName")) {
                    $random = rand(1, 20);
                    $fileNameNew = $random . $fileName;
                } else {
                    $fileNameNew = $fileName;
                }
                $fileDestination = "$upload_dir/$fileNameNew";
                move_uploaded_file($fileTmpName, $fileDestination);
                $response[] = ['status' => 'success', 'message' => 'File Uploaded Successfully', 'fileName' => $fileNameNew];
            } else {
                $response[] = ['status' => 'error', 'message' => 'File size Too Big', 'fileName' => $fileName];
            }
        } else {
            $response[] = ['status' => 'error', 'message' => 'There was an error uploading your file!', 'fileName' => $fileName];
        }
    }
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-Cloud</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.png">
    <style>
        .dragover {
            border-color: #007bff;
            background-color: #e9f7ff;
            transition: background-color 0.3s ease;
        }

        #dropzone {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: #999;
        }

        #dropzone.dragover {
            border-color: #000C66;
            background-color: #e0e7ff;
            color: #000C66;
        }

        .progress-bar {
            transition: width 0.3s ease;
            background-color: #3CB371;
            /* Change the color to green */
        }

        #uploadStatus {
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        #uploadStatus.fade-out {
            opacity: 0;
        }

        #fileList {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="navbar justify-content-between" style="margin-top: 2rem;">
            <a class="navbar-brand" style="color:#000C66; font-weight: 650; font-size: 27;">M-Cloud</a>
            <a href="<?php echo $redirectUrl; ?>">
                <button class="btn" style="background-color: #000C66; color: white; border-radius: 10px; margin-bottom: 0;"><?php echo $buttonText; ?></button>
            </a>
        </nav>
        <h2 class="text-center mb-4" style="color:#000C66; margin-top:3rem; font-weight: 700">Upload Your Files</h2>
        <form id="fileForm" method="POST" action="#" enctype="multipart/form-data">
            <div id="dropzone" class="text-center" style="height: 15rem">
                Drag & Drop your files here or click to select files
                <input type="file" id="fileInput" name="files[]" multiple class="d-none">
            </div>
            <div class="progress mt-3 d-none">
                <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
            </div>
            <div id="uploadStatus" class="mt-3"></div>
            <div id="fileList" class="mt-3"></div>
            <button type="button" id="uploadButton" class="btn btn-block mt-3 d-none" style="background-color: #000C66;color:white;font-weight:700">Upload Files</button>
        </form>
    </div>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">Manee || Nikas © 2024</span><br>
            <span class="text-muted">The Official Child of DriveKas</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropzone = document.getElementById('dropzone');
            var fileInput = document.getElementById('fileInput');
            var uploadButton = document.getElementById('uploadButton');
            var uploadStatus = document.getElementById('uploadStatus');
            var progressBar = document.querySelector('.progress-bar');
            var progressContainer = document.querySelector('.progress');
            var fileList = document.getElementById('fileList');
            var files = [];

            dropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', function (e) {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                files = e.dataTransfer.files;
                handleFiles(files);
            });

            dropzone.addEventListener('click', function () {
                fileInput.click();
            });

            fileInput.addEventListener('change', function (e) {
                files = e.target.files;
                handleFiles(files);
            });

            uploadButton.addEventListener('click', function () {
                if (files.length > 0) {
                    fileList.innerHTML = ''; // Clear the file list
                    uploadFiles(files);
                }
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    fileList.innerHTML = '';
                    Array.from(files).forEach(function (file) {
                        var listItem = document.createElement('div');
                        listItem.className = 'alert alert-secondary';
                        listItem.textContent = file.name;
                        fileList.appendChild(listItem);
                    });
                    uploadButton.classList.remove('d-none');
                }
            }

            function uploadFiles(files) {
                var formData = new FormData();
                Array.from(files).forEach(function (file) {
                    formData.append('files[]', file);
                });

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '#', true);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        var percentComplete = (e.loaded / e.total) * 100;
                        progressBar.style.width = percentComplete + '%';
                    }
                };

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        response.forEach(function (res) {
                            var statusAlert = document.createElement('div');
                            statusAlert.className = 'alert alert-' + (res.status === 'success' ? 'success' : 'danger');
                            statusAlert.textContent = res.message + ': ' + res.fileName;
                            uploadStatus.appendChild(statusAlert);
                        });

                        progressBar.style.width = '0%';
                        progressContainer.classList.add('d-none');

                        setTimeout(function () {
                            uploadStatus.classList.add('fade-out');
                            setTimeout(function () {
                                uploadStatus.innerHTML = '';
                                uploadStatus.classList.remove('fade-out');
                                fileList.innerHTML = ''; // Clear the uploaded files list
                            }, 500);
                        }, 3000);
                    } else {
                        uploadStatus.innerHTML = '<div class="alert alert-danger">File upload failed.</div>';
                    }
                };

                progressContainer.classList.remove('d-none');
                xhr.send(formData);
            }
        });
    </script>
</body>

</html>
